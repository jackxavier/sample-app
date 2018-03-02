<?php

namespace Zsa\Pip\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Zsa\Pip\Entity\Pip;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\Stdlib\ArrayUtils;

/**
 * Class PipArrayCollection
 *
 * @package Zsa\Pip\Collection
 */
class PipArrayCollection
{
    /**
     * @var ArrayCollection|\SplObjectStorage|Pip[]
     */
    protected $pips;

    /**
     * PipComparativeCollection constructor.
     *
     * @param PipContainerInterface $pipContainer
     */
    public function __construct()
    {
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
            $this->pips->add($pip);
        }

        return $this;
    }

    /**
     * @param $pipsArray
     *
     * @return $this
     */
    public function populate($pipsArray)
    {
        if (!is_array($pipsArray) && !$pipsArray instanceof \Traversable) {
            return $this;
        }

        if ($pipsArray instanceof \Traversable) {
            $pipsArray = ArrayUtils::iteratorToArray($pipsArray);
        }

        if (empty($pipsArray)) {
            return $this;
        }
        /** @var Pip $pip */
        foreach ($pipsArray as $pip) {
            $this->addPip($pip);

            $children = $pip->getAllChildren();

            if (empty($children)) {
                continue;
            }
            /** @var Pip $child */
            foreach ($children as $child) {
                $this->addPip($child);
            }
        }

        return $this;
    }

    /**
     * @param Pip $pip
     *
     * @return $this
     */
    public function removePip(Pip $pip)
    {
        if ($this->pips->contains($pip)) {
            $this->pips->removeElement($pip);
        }

        return $this;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->pips = new ArrayCollection();
    }

    /**
     * @return Paginator
     */
    public function toPaginator()
    {
        return new Paginator(new ArrayAdapter($this->pips->toArray()));
    }
}