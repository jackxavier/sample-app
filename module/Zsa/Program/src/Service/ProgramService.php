<?php

namespace Zsa\Program\Service;

use Axmit\Dao\Criterion\Filter;
use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Entity\UserInterface;
use Zend\Paginator\Paginator;
use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Dto\PipRelation\PipRelationTo;
use Zsa\Pip\Service\PipAttachServiceInterface;
use Zsa\Pip\Service\PipManagementService;
use Zsa\Program\Dao\Hydrator\ProgramFilteringHydrator;
use Zsa\Program\Dao\ProgramDaoInterface;
use Zsa\Program\Dto\Program\ProgramEditTo;
use Zsa\Program\Entity\Program;
use Zsa\Util\Enum\WorkflowStatusEnum;

/**
 * Class ProgramService
 *
 * @package Zsa\Program\Service
 */
class ProgramService implements PipAttachServiceInterface
{
    /**
     * @var ProgramDaoInterface
     */
    protected $programDao;

    /**
     * @var ProgramFilteringHydrator
     */
    protected $programFilteringHydrator;

    /**
     * @var PipManagementService
     */
    protected $pipManagementService;

    /**
     * ProgramService constructor.
     *
     * @param ProgramDaoInterface      $programDao
     * @param ProgramFilteringHydrator $programFilteringHydrator
     * @param PipManagementService     $pipManagementService
     */
    public function __construct(
        ProgramDaoInterface $programDao,
        ProgramFilteringHydrator $programFilteringHydrator,
        PipManagementService $pipManagementService
    ) {
        $this->programDao               = $programDao;
        $this->programFilteringHydrator = $programFilteringHydrator;
        $this->pipManagementService     = $pipManagementService;
    }

    /**
     * @param ProgramEditTo      $editTo
     * @param User|UserInterface $user
     *
     * @return Program|null
     */
    public function create(ProgramEditTo $editTo, UserInterface $user)
    {
        $program = new Program();
        $program->setTitle($editTo->getTitle())
                ->setDescription($editTo->getDescription())
                ->setCreatedBy($user);

        $projects = $editTo->getProjects();

        foreach ($projects as $project) {
            $program->addProject($project);
        }

        $controller = $editTo->getController() ?: $user;
        $program->setController($controller);

        $this->programDao->save($program);

        return $program;
    }

    /**
     * @param $id
     *
     * @return Program|null|object
     */
    public function find($id)
    {
        return $this->programDao->find($id);
    }

    /**
     * @param Program $program
     * @param bool    $removePips
     *
     * @return void
     */
    public function remove(Program $program, $removePips = false)
    {
        if ($removePips) {
            $this->pipManagementService->removeRelatedPips($program);
        } else {
            $this->pipManagementService->changeRelatedPipsStatuses($program);
            $program->getPips()->clear();
        }

        $this->programDao->remove($program);
    }

    /**
     * @param Program $program
     * @param bool    $removePips
     *
     * @return void
     */
    public function archive(Program $program, $removePips = false)
    {
        if ($removePips) {
            $this->pipManagementService->removeRelatedPips($program);
        } else {
            $this->pipManagementService->changeRelatedPipsStatuses($program);
            $program->getPips()->clear();
        }

        $program->setStatus(WorkflowStatusEnum::VALUE_STATUS_ARCHIVED_STR);

        $this->programDao->save($program);
    }

    /**
     * @param array $params
     *
     * @return Paginator
     */
    public function fetchAll(array $params = [])
    {
        $filter      = new Filter();
        $filterArray = [];

        foreach ($params as $key => $paramName) {
            if (isset($paramName)) {
                $filterArray[$key] = $paramName;
            }
        }

        $filter->fromArray($filterArray, $this->programFilteringHydrator);

        return $this->programDao->findPaginatedByFilter($filter);
    }

    /**
     * @param Program            $program
     * @param ProgramEditTo      $editTo
     * @param User|UserInterface $user
     *
     * @return Program|null
     */
    public function update(Program $program, ProgramEditTo $editTo, UserInterface $user)
    {
        $program->setTitle($editTo->getTitle())
                ->setDescription($editTo->getDescription())
                ->setStatus($editTo->getStatus())
                ->setUpdatedBy($user)
                ->setUpdatedOn(new \DateTime())
                ->updateProjects($user, $editTo->getProjects())
                ->updateProtocols($user, $editTo->getProtocols());

        if ($editTo->getController()) {
            $this->changeController($program, $editTo->getController());
        }

        $this->programDao->save($program);

        return $program;
    }

    /**
     * @param Program $program
     * @param bool    $status
     *
     * @return Program|null
     */
    public function changeStatus(Program $program, $status = false)
    {
        $program->setStatus($status)
                ->setUpdatedOn(new \DateTime());

        $this->programDao->save($program);

        return $program;
    }

    /**
     * @param PipContainerInterface|Program $program
     * @param PipRelationTo                 $relationTo
     * @param UserInterface|User            $user
     *
     * @return bool
     */
    public function attachPip(PipContainerInterface $program, PipRelationTo $relationTo, UserInterface $user)
    {
        $program->assignPip($relationTo);
        $program->setUpdatedBy($user)
                ->setUpdatedOn(new \DateTime());

        $this->programDao->save($program);

        return true;
    }

    /**
     * @param PipContainerInterface|Program $program
     * @param PipRelationTo                 $relationTo
     * @param UserInterface|User            $user
     *
     * @return bool
     */
    public function detachPip(PipContainerInterface $program, PipRelationTo $relationTo, UserInterface $user)
    {
        $program->disassignPip($relationTo);
        $program->setUpdatedBy($user)
                ->setUpdatedOn(new \DateTime());

        $this->programDao->save($program);

        return true;
    }

    /**
     * @param Program $program
     * @param User    $newController
     *
     * @return Program
     */
    public function changeController(Program $program, User $newController)
    {
        if ($program->getController()->getId() == $newController->getId()) {
            return $program;
        }

        $program->setController($newController);

        return $program;
    }
}