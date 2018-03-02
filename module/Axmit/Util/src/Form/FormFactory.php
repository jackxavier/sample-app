<?php

namespace Axmit\Util\Form;

use Interop\Container\ContainerInterface;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FormFactory
 *
 * @package Axmit\Util\Form
 */
abstract class FormFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Form
     */
    abstract public function __invoke(ContainerInterface $container, $requestedName, array $options = null);

    /**
     * @param ContainerInterface|null $container
     *
     * @return mixed
     */
    abstract public function getInputFilter(ContainerInterface $container = null, $options = null);

    /**
     * @param ContainerInterface $container
     *
     * @return HydratorInterface
     */
    abstract public function getHydrator(ContainerInterface $container = null);

    /**
     * @param Form               $form
     * @param ContainerInterface $container
     * @param                    $object
     *
     * @return Form
     */
    public function attachToForm(Form $form, ContainerInterface $container, $object, $options = null)
    {
        $inputFilter = $this->getInputFilter($container, $options);
        $form->setInputFilter($inputFilter ?: new InputFilter());

        $hydrator = $this->getHydrator($container);
        $form->setHydrator($hydrator ?: new ClassMethods());
        $form->setObject($object);

        return $form;
    }

}