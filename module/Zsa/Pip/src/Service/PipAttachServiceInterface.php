<?php


namespace Zsa\Pip\Service;

use Axmit\UserCore\Entity\UserInterface;
use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Dto\PipRelation\PipRelationTo;

/**
 * Interface PipAttachServiceInterface
 *
 * @package Zsa\Pip\Service
 */
interface PipAttachServiceInterface
{
    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * @param PipContainerInterface $pipContainer
     * @param PipRelationTo         $relationTo
     * @param UserInterface         $user
     *
     * @return bool
     */
    public function attachPip(PipContainerInterface $pipContainer, PipRelationTo $relationTo, UserInterface $user);

    /**
     * @param PipContainerInterface $pipContainer
     * @param PipRelationTo         $relationTo
     * @param UserInterface         $user
     *
     * @return bool
     */
    public function detachPip(PipContainerInterface $pipContainer, PipRelationTo $relationTo, UserInterface $user);
}