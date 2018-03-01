<?php

namespace Zsa\Pip\Service;

use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Entity\UserInterface;
use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Dao\PipDaoInterface;
use Zsa\Pip\Dto\PipRelation\PipRelationTo;
use Zsa\Pip\Entity\Pip;

/**
 * Class PipSelfAttachService
 *
 * @package Zsa\Pip\Service
 */
class PipSelfAttachService implements PipAttachServiceInterface
{
    /**
     * @var PipDaoInterface
     */
    protected $pipDao;

    /**
     * PipSelfAttachService constructor.
     *
     * @param PipDaoInterface $pipDao
     */
    public function __construct(PipDaoInterface $pipDao)
    {
        $this->pipDao = $pipDao;
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
     * @param PipContainerInterface|Pip $pip
     * @param PipRelationTo             $relationTo
     * @param UserInterface|User        $user
     *
     * @return bool
     */
    public function attachPip(PipContainerInterface $pip, PipRelationTo $relationTo, UserInterface $user)
    {
        $pip->assignPip($relationTo);
        $pip->setUpdatedBy($user)
            ->setUpdatedOn(new \DateTime());

        return $this->doSave($pip);
    }

    /**
     * @param PipContainerInterface|Pip $pip
     * @param PipRelationTo             $relationTo
     * @param UserInterface|User        $user
     *
     * @return bool
     */
    public function detachPip(PipContainerInterface $pip, PipRelationTo $relationTo, UserInterface $user)
    {
        $pip->disassignPip($relationTo);
        $pip->setUpdatedBy($user)
            ->setUpdatedOn(new \DateTime());

        return $this->doSave($pip);
    }

    /**
     * @param Pip $pip
     *
     * @return bool
     */
    public function doSave(Pip $pip)
    {
        try {
            $result = $this->pipDao->tryToSave($pip);
        } catch (\Exception $exception) {
            $result = false;
        }

        if (!$result) {
            return false;
        }

        return true;
    }
}
