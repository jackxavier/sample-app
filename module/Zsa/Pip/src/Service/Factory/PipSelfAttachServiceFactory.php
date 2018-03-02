<?php

namespace Zsa\Pip\Service\Factory;

use Zsa\Pip\Dao\PipDaoInterface;
use Zsa\Pip\Service\PipSelfAttachService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PipSelfAttachServiceFactory
 *
 * @package Zsa\Pip\Service\Factory
 */
class PipSelfAttachServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PipSelfAttachService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var PipDaoInterface $pipDao */
        $pipDao = $container->get(PipDaoInterface::class);

        return new PipSelfAttachService($pipDao);
    }
}
