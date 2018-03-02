<?php

namespace Zsa\Pip\Service\Factory;

use Zsa\Pip\Dao\PipDaoInterface;
use Zsa\Pip\Service\PipManagementService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PipManagementServiceFactory
 *
 * @package Zsa\Pip\Service\Factory
 */
class PipManagementServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PipManagementService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var PipDaoInterface $pipDao */
        $pipDao = $container->get(PipDaoInterface::class);

        return new PipManagementService($pipDao);
    }
}
