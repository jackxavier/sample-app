<?php

namespace Axmit\Util\Form;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Form\Form as ZendForm;
use Zend\Form\FormInterface;

/**
 * Class EventsProviderForm
 *
 * @package Axmit\Util\Form
 */
class EventsProviderForm extends ZendForm implements EventManagerAwareInterface
{
    /**
     * Triggered on prepare() method call
     */
    const EVENT_PREPARE = 'onPrepare';
    /**
     * Triggered on init() method call, parent::init() should be called on target form
     */
    const EVENT_INIT = 'init';
    /**
     * Triggered before validation is performed
     */
    const EVENT_IS_VALID = 'isValid';
    /**
     * Triggered before bind is performed
     */
    const EVENT_BIND = 'bind';

    use EventManagerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->getEventManager()->trigger(static::EVENT_INIT, $this);

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        $this->getEventManager()->trigger(static::EVENT_PREPARE, $this);

        return parent::prepare();
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        $this->getEventManager()->trigger(static::EVENT_IS_VALID, $this, ['data' => $this->data]);

        return parent::isValid();
    }

    /**
     * {@inheritdoc}
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        $this->getEventManager()->trigger(static::EVENT_BIND, $this, ['object' => $object]);

        return parent::bind($object, $flags);
    }
}
