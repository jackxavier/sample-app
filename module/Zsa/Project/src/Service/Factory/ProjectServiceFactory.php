<?php

namespace Zsa\Project\Service\Factory;

use Zsa\Pip\Service\PipManagementService;
use Zsa\Project\Dao\Doctrine\ProjectAttendeeDoctrineDao;
use Zsa\Project\Dao\Hydrator\ProjectFilteringHydrator;
use Zsa\Project\Dao\ProjectDaoInterface;
use Zsa\Project\Service\ProjectService;
use Psr\Container\ContainerInterface;

/**
 * Class ProjectServiceFactory
 *
 * @package Zsa\Project\Service\Factory
 */
class ProjectServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProjectService
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ProjectDaoInterface $projectDao */
        $projectDao = $container->get(ProjectDaoInterface::class);
        /** @var ProjectAttendeeDoctrineDao $projectAttendeeDao */
        $projectAttendeeDao = $container->get(ProjectAttendeeDoctrineDao::class);
        /** @var ProjectFilteringHydrator $projectFilteringHydrator */
        $projectFilteringHydrator = $container->get(ProjectFilteringHydrator::class);
        /** @var PipManagementService $pipManagementService */
        $pipManagementService = $container->get(PipManagementService::class);

        return new ProjectService($projectDao, $projectAttendeeDao, $projectFilteringHydrator, $pipManagementService);
    }
}