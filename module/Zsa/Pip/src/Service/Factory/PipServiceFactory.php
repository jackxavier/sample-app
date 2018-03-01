<?php

namespace Zsa\Pip\Service\Factory;

use Zsa\Pip\Dao\Doctrine\PipAssigneeDoctrineDao;
use Zsa\Pip\Dao\Doctrine\PipDoctrineDao;
use Zsa\Pip\Dispatcher\PipAttachDispatcher;
use Zsa\Pip\Service\PipService;
use Psr\Container\ContainerInterface;

/**
 * Class PipServiceFactory
 *
 * @package Zsa\Pip\Service\Factory
 */
class PipServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PipService
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var PipDoctrineDao $pipDao */
        $pipDao = $container->get(PipDoctrineDao::class);
        /** @var PipAssigneeDoctrineDao $pipAssigneeDao */
        $pipAssigneeDao = $container->get(PipAssigneeDoctrineDao::class);
        /** @var PipAttachDispatcher $pipAttachDispatcher */
        $pipAttachDispatcher = $container->get(PipAttachDispatcher::class);

        return new PipService($pipDao, $pipAssigneeDao, $pipAttachDispatcher);
    }
}