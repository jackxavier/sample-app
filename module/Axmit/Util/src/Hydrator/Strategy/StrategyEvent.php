<?php

namespace Axmit\Util\Hydrator\Strategy;

use Zend\EventManager\Event;

/**
 * Class StrategyEvent
 *
 * @package Axmit\Util\Hydrator\Strategy
 */
class StrategyEvent extends Event
{

    const EVENT_HYDRATE = 'hydrate';
    const EVENT_EXTRACT = 'extract';

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var object
     */
    protected $object;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param object $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

}