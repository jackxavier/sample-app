<?php

namespace Axmit\Util\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class ArrayStrategy
 *
 * @package Axmit\Util\Hydrator\Strategy
 */
class ArrayStrategy implements StrategyInterface
{
    /**
     * @var StrategyInterface
     */
    protected $hydratorStrategy;

    /**
     * ArrayDoctrineObject constructor.
     *
     * @param StrategyInterface $strategy
     */
    public function __construct(StrategyInterface $strategy)
    {
        $this->hydratorStrategy = $strategy;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed  $value  The original value.
     * @param object $object (optional) The original object for context.
     *
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        $collection = [];

        if (!$value) {
            return $collection;
        }

        if (!is_array($value)) {
            $collection[] = $this->hydratorStrategy->extract($value);

            return $collection;
        }

        foreach ($value as $item) {
            $collection[] = $this->hydratorStrategy->extract($item);
        }

        return $collection;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array $data  (optional) The original data for context.
     *
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        $collection = [];

        if (!$value) {
            return $collection;
        }

        if (!is_array($value)) {
            $collection[] = $this->hydratorStrategy->hydrate($value);

            return $collection;
        }

        foreach ($value as $item) {
            $collection[] = $this->hydratorStrategy->hydrate($item);
        }

        return $collection;
    }
}