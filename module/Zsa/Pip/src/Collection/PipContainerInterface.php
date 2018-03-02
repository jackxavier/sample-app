<?php

namespace Zsa\Pip\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipRelation;

/**
 * Interface PipCollectionInterface
 *
 * @package Zsa\Pip\Entity
 */
interface PipContainerInterface
{
    /**
     * @param mixed $pip
     * @param null  $type
     *
     * @return PipRelation
     */
    public function addPip($pip, $type = null);

    /**
     * @param mixed $pip
     * @param null  $type
     *
     * @return
     */
    public function removePip($pip, $type = null);

    /**
     * @return PipRelation[]
     */
    public function getPips();

    /**
     * @return bool
     */
    public function hasPips();

    /**
     * @param mixed $pip
     * @param null  $type
     *
     * @return bool
     */
    public function hasPip($pip, $type = null);

    /**
     * @param mixed $pip
     *
     * @return Pip
     */
    public function assignPip($pip);

    /**
     * @return array|Pip[]|ArrayCollection
     */
    public function getAssignedPips();
}