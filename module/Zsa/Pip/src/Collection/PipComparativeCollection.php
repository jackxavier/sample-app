<?php

namespace Zsa\Pip\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Zsa\Pip\Entity\Pip;
use SplObjectStorage;

/**
 * Class PipComparativeCollection
 *
 * @package Guild\Pip\Service
 */
class PipComparativeCollection
{
    /**
     * @var PipContainerInterface
     */
    protected $pipContainer;

    /**
     * @var SplObjectStorage|ArrayCollection|Pip[]
     */
    protected $pips;

    /**
     * @var SplObjectStorage|ArrayCollection|Pip[]
     */
    protected $new;

    /**
     * PipComparativeCollection constructor.
     *
     * @param PipContainerInterface $pipContainer
     */
    public function __construct(PipContainerInterface $pipContainer)
    {
        $this->pipCollection = $pipContainer;
        $this->reset();
    }

    /**
     * @param Pip $pip
     *
     * @return $this
     */
    public function addPip(Pip $pip)
    {
        if (!$this->pips->contains($pip)) {
            $this->pips->attach($pip);
        }

        if (!$this->isExist($pip)) {
            $this->new->attach($pip);
        }

        return $this;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->pips = new SplObjectStorage();
        $this->new  = new SplObjectStorage();
    }

    /**
     * @return Pip[]
     */
    public function findRemoved()
    {
        /** @var ArrayCollection $pips */
        $pips     = $this->pipCollection->getPips();
        $assigned = new ArrayCollection($pips->toArray());

        foreach ($this->pips as $pip) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('id', $pip->getId()));

            $found = $assigned->matching($criteria);

            if ($found->count() > 0) {
                $assigned->removeElement($found->first());
            }
        }

        return $assigned->toArray();
    }

    /**
     * @param Pip $pip
     *
     * @return bool
     */
    public function isExist(Pip $pip)
    {
        return $this->pipCollection->hasPip($pip);
    }

    /**
     * @return Pip[]
     */
    public function getNew()
    {
        return iterator_to_array($this->new);
    }
}