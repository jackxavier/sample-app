<?php

namespace Zsa\Pip\Util;

use Doctrine\Common\Collections\ArrayCollection;
use Traversable;

/**
 * Class ObjectComparativeCollection
 *
 * @package Zsa\Pip\Util
 */
class ObjectComparativeCollection implements \IteratorAggregate
{
    /**
     * @var ArrayCollection
     */
    protected $collection;

    /**
     * ObjectComparativeCollection constructor.
     *
     * @param array $collection
     */
    public function __construct($collection = [])
    {
        $this->collection = new ArrayCollection($collection);
    }

    /**
     * @return ArrayCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param      $element
     * @param null $callable
     *
     * @return bool
     */
    public function contains($element, $callable = null)
    {
        if ($callable) {
            foreach ($this->collection as $item) {
                if (call_user_func($callable, $element, $item)) {
                    return true;
                }
            }
        } else {
            return $this->collection->contains($element);
        }

        return false;
    }

    /**
     * @param $element
     *
     * @return void
     */
    public function add($element)
    {
        $this->collection->add($element);
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->collection;
    }
}
