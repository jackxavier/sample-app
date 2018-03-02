<?php

namespace Zsa\Pip\Service;

use Axmit\Dao\Criterion\Filter;
use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Entity\UserInterface;
use Zsa\Pip\Collection\PipArrayCollection;
use Zsa\Pip\Dao\Hydrator\PipFilteringHydrator;
use Zsa\Pip\Dao\PipAssigneeDaoInterface;
use Zsa\Pip\Dao\PipDaoInterface;
use Zsa\Pip\Dispatcher\PipAttachDispatcher;
use Zsa\Pip\Dto\Pip\PipEditTo;
use Zsa\Pip\Dto\PipAttach\PipAttachTo;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipAssignee;


/**
 * Class PipService
 *
 * @package Zsa\Pip\Service
 */
class PipService
{
    /**
     * @var PipDaoInterface
     */
    protected $pipDao;

    /**
     * @var PipAssigneeDaoInterface
     */
    protected $pipAssigneeDao;

    /**
     * @var PipAttachDispatcher
     */
    protected $pipAttachDispatcher;

    /**
     * PipService constructor.
     *
     * @param PipDaoInterface         $pipDao
     * @param PipAssigneeDaoInterface $pipAssigneeDao
     * @param PipAttachDispatcher     $pipAttachDispatcher
     */
    public function __construct(
        PipDaoInterface $pipDao,
        PipAssigneeDaoInterface $pipAssigneeDao,
        PipAttachDispatcher $pipAttachDispatcher
    ) {
        $this->pipDao              = $pipDao;
        $this->pipAssigneeDao      = $pipAssigneeDao;
        $this->pipAttachDispatcher = $pipAttachDispatcher;
    }

    /**
     * @param $id
     *
     * @return \Zsa\Pip\Entity\Pip|null|object
     */
    public function find($id)
    {
        return $this->pipDao->find($id);
    }

    /**
     * @param array $params
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll($params = [])
    {
        $filter      = new Filter();
        $filterArray = [];

        foreach ($params as $key => $paramName) {
            if (isset($paramName)) {
                $filterArray[$key] = $paramName;
            }
        }

        $filter->fromArray($filterArray, new PipFilteringHydrator());

        $pips          = $this->pipDao->findByFilter($filter);
        $pipCollection = new PipArrayCollection();

        if (!empty($pips)) {
            $pipCollection->populate($pips);
        }

        return $pipCollection->toPaginator();
    }

    /**
     * @param array $params
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetchUserPips(User $user, $params = [])
    {
        $filter      = new Filter();
        $filterArray = [];

        foreach ($params as $key => $paramName) {
            if (isset($paramName)) {
                $filterArray[$key] = $paramName;
            }
        }

        $filter->fromArray($filterArray, new PipFilteringHydrator());

        $pips          = $this->pipDao->findUserPips($user, $filter);
        $pipCollection = new PipArrayCollection();

        if (!empty($pips)) {
            $pipCollection->populate($pips);
        }

        return $pipCollection->toPaginator();
    }

    /**
     * @param PipEditTo          $editTo
     * @param UserInterface|User $user
     *
     * @return Pip|null
     */
    public function create(PipEditTo $editTo, UserInterface $user)
    {
        $pip = new Pip();

        $pip->bindPip($editTo);

        if (!empty($editTo->getStatus())) {
            $pip->setStatus($editTo->getStatus());
        }

        $pip->setOwner($user);
        $pip->setCreatedBy($user);

        $assignee = $editTo->getAssignee() ?: $user;

        $pip->addAssignee($assignee);
        $this->pipDao->tryToSave($pip);

        return $pip;
    }

    /**
     * @param PipEditTo          $editTo
     * @param PipAttachTo        $pipAttachTo
     * @param UserInterface|User $user
     *
     * @return Pip|null
     */
    public function createAndAttach(PipEditTo $editTo, PipAttachTo $pipAttachTo, UserInterface $user)
    {
        $pip = new Pip();

        $pip->bindPip($editTo);

        if (!empty($editTo->getStatus())) {
            $pip->setStatus($editTo->getStatus());
        }

        $pip->setOwner($user);
        $pip->setCreatedBy($user);

        $assignee = $editTo->getAssignee() ?: $user;

        $pip->addAssignee($assignee);

        if ($pipAttachTo->getService()) {
            $related  = $this->pipAttachDispatcher->fetchRelated($pipAttachTo);
            $relation = $related->addPip($pip, $pipAttachTo->getRelationType());
        }

        $this->pipDao->tryToSave($pip);

        return $pip;
    }

    /**
     * @param Pip                $pip
     * @param PipEditTo          $editTo
     * @param UserInterface|User $user
     *
     * @return Pip|null
     */
    public function update(Pip $pip, PipEditTo $editTo, UserInterface $user)
    {
        $pip->bindPip($editTo);

        if (!empty($editTo->getStatus()) && $pip->getStatus() !== $editTo->getStatus()) {
            $pip->setStatus($editTo->getStatus());
        }

        $pip->setUpdatedOn(new \DateTime())
            ->setUpdatedBy($user);

        if ($editTo->getAssignee()) {
            $pip = $this->doAssignPip($pip, $editTo->getAssignee(), $user);
        }

        $this->pipDao->tryToSave($pip);

        return $pip;
    }

    /**
     * @param Pip $pip
     *
     * @return void
     */
    public function remove(Pip $pip)
    {
        $this->pipDao->tryToRemove($pip);
    }

    /**
     * @param Pip  $pip
     * @param User $user
     * @param User $assignBy
     *
     * @return Pip|null
     */
    public function assignPip(Pip $pip, User $user, User $assignBy)
    {
        $this->pipDao->tryToSave($this->doAssignPip($pip, $user, $assignBy));

        return $pip;
    }

    /**
     * @param Pip  $pip
     * @param User $user
     * @param User $assignBy
     *
     * @return Pip
     */
    public function doAssignPip(Pip $pip, User $user, User $assignBy)
    {
        if ($pip->isSolver($user)) {
            return $pip;
        }

        /** @var PipAssignee $solver */
        $solver = $pip->getSolver();
        if ($solver) {
            if ($solver->getUser()->getId() !== $assignBy->getId()) {
                $pip->removeAssignee($solver);
            } else {
                $solver->setStatus(PipAssignee::VALUE_STATUS_CONTROLLER);
                $this->pipAssigneeDao->tryToSave($solver);
            }
        }

        $pip->addAssignee($user);
        $pip->removeChildren();
        $pip->setParentPip(null);

        return $pip;
    }

    /**
     * @param Pip           $pip
     * @param               $status
     * @param UserInterface $user
     *
     * @return Pip|null
     */
    public function updateStatus(Pip $pip, $status, UserInterface $user)
    {
        $pip->setStatus($status);
        $pip->setUpdatedOn(new \DateTime())
            ->setUpdatedBy($user);

        $this->pipDao->tryToSave($pip);

        return $pip;
    }

}
