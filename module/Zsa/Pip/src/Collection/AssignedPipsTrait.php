<?php

namespace Zsa\Pip\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Zsa\Pip\Dto\PipRelation\PipRelationTo;
use Zsa\Pip\Entity\Enum\PipStatusEnum;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipRelation;

/**
 * Trait AssignedPipsTrait
 *
 * @package Zsa\Pip\Entity
 */
trait AssignedPipsTrait
{
    /**
     * @return PipRelation[]|Collection
     */
    abstract protected function getPips();

    /**
     * @param Pip  $pip
     * @param null $type
     *
     * @return mixed
     */
    abstract protected function addPip(Pip $pip, $type = null);

    /**
     * @return array|Pip[]|ArrayCollection
     */
    public function getAssignedPips()
    {
        $pipRelations       = $this->getPips()->toArray();
        $pipArrayCollection = new ArrayCollection();

        /** @var PipRelation $pipRelation */
        foreach ($pipRelations as $pipRelation) {
            /** @var Pip $pip */
            $pip = $pipRelation->getPip();

            if (!$pipArrayCollection->contains($pip)) {
                $pipArrayCollection->add($pip);
            }

            if (!$pip->hasChildren()) {
                continue;
            }

            foreach ($pip->getAllChildren() as $pipChild) {
                if (!$pipArrayCollection->contains($pipChild)) {
                    $pipArrayCollection->add($pipChild);
                }
            }
        }

        return $pipArrayCollection;
    }

    /**
     * @param Pip|PipRelationTo|PipRelation $pip
     *
     * @return Pip
     */
    public function assignPip($pip)
    {
        if (!$pip instanceof Pip && !$pip instanceof PipRelationTo && !$pip instanceof PipRelation) {
            return $pip;
        }

        $assignedPip  = $pip instanceof Pip ?: $pip->getPip();
        $type         = $pip instanceof Pip ? $pip->getType() : $pip->getRelationType();
        $assignedPips = $this->getAssignedPips();

        if ($assignedPips->contains($assignedPip)) {
            return $pip;
        }

        if ($assignedPip->isInBacklog() || $assignedPip->isOpened()) {
            $assignedPip->setStatus(PipStatusEnum::VALUE_ACTIVE);
        }

        /** @var Pip $child */
        foreach ($assignedPip->getAllChildren() as $child) {
            if ($child->isInBacklog() || $child->isOpened()) {
                $child->setStatus(PipStatusEnum::VALUE_ACTIVE);
            }
        }

        $this->addPip($assignedPip, $type);

        return $pip;
    }

    /**
     * @param $pip
     *
     * @return mixed
     */
    public function disassignPip($pip)
    {
        if (!$pip instanceof Pip && !$pip instanceof PipRelationTo && !$pip instanceof PipRelation) {
            return $pip;
        }

        $disassignedPip = $pip instanceof Pip ?: $pip->getPip();
        $type           = $pip instanceof Pip ? $pip->getType() : $pip->getRelationType();

        $this->removePip($disassignedPip, $type);

        /** @var Pip $child */
        foreach ($disassignedPip->getAllChildren() as $child) {
            $this->removePip($child, $type);

            if (!$disassignedPip->hasSolvingRelations() && !$disassignedPip->isInBacklog()) {
                $child->setStatus(PipStatusEnum::VALUE_OPENED);
            }
        }

        if (!$disassignedPip->hasSolvingRelations() && !$disassignedPip->isInBacklog()) {
            $disassignedPip->setStatus(PipStatusEnum::VALUE_OPENED);
        }

        return $pip;
    }

    /**
     * @param mixed $pip
     * @param null  $type
     *
     * @return bool
     */
    public function hasPip($pip, $type = null)
    {
        return $this->getPip($pip, $type) ? true : false;
    }

    /**
     * @param mixed       $pip
     * @param string|null $type
     *
     * @return PipRelation|null
     */
    public function getPip($pip, $type = null)
    {
        /** @var ArrayCollection $pips */
        $pips = $this->getPips();

        if ($pip instanceof PipRelation) {
            if ($pips->contains($pip)) {
                return $pip;
            }
        }

        if ($pip instanceof PipRelationTo) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('pip', $pip->getPip()));

            if ($type || $pip->getRelationType()) {
                $criteria->andWhere(Criteria::expr()->eq('type', $type ?: $pip->getRelationType()));
            }

            return $pips->matching($criteria)->last();
        }

        if ($pip instanceof Pip) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('pip', $pip));

            if ($type) {
                $criteria->andWhere(Criteria::expr()->eq('type', $type));
            }

            return $pips->matching($criteria)->last();
        }

        return null;
    }
}