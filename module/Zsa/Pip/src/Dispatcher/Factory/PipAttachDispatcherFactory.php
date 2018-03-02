<?php

namespace Zsa\Pip\Dispatcher\Factory;

use Zsa\Pip\Dispatcher\PipAttachDispatcher;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PipAttachDispatcherFactory
 *
 * @package Zsa\Pip\Dispatcher\Factory
 */
class PipAttachDispatcherFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PipAttachDispatcher
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PipAttachDispatcher($container);
    }
}
