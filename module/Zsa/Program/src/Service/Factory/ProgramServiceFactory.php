<?php

namespace Zsa\Program\Service\Factory;

use Zsa\Pip\Service\PipManagementService;
use Zsa\Program\Dao\Hydrator\ProgramFilteringHydrator;
use Zsa\Program\Dao\ProgramDaoInterface;
use Zsa\Program\Service\ProgramService;
use Interop\Container\ContainerInterface;

/**
 * Class ProgramServiceFactory
 *
 * @package Zsa\Program\Service\Factory
 */
class ProgramServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProgramService
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ProgramDaoInterface $programDao */
        $programDao = $container->get(ProgramDaoInterface::class);
        /** @var  $programFilteringHydrator */
        $programFilteringHydrator = $container->get(ProgramFilteringHydrator::class);
        /** @var PipManagementService $pipManagementService */
        $pipManagementService = $container->get(PipManagementService::class);

        return new ProgramService($programDao, $programFilteringHydrator, $pipManagementService);
    }
}