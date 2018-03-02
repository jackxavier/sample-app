<?php

namespace Api\Project\V1\Rpc\GetReport;

use Doctrine\ORM\EntityManager;
use Epos\UserCore\Service\UserService;
use Zsa\Reporting\Project\Processor\ProjectSpreadsheetProcessor;
use Zsa\Reporting\Project\Service\ProjectReportService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProjectReportControllerFactory
 *
 * @package Api\Project\V1\Rpc\GetReport
 */
class ProjectReportControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ProjectReportController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserService $userService */
        $userService = $container->get(UserService::class);
        /** @var ProjectReportService $projectService */
        $projectService = $container->get(ProjectReportService::class);
        /** @var ProjectSpreadsheetProcessor $processor */
        $processor = $container->get(ProjectSpreadsheetProcessor::class);
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        $entityManager->getFilters()->disable('project_ignore_status');
        $entityManager->getFilters()->disable('protocol_ignore_status');

        return new ProjectReportController($userService, $projectService, $processor);
    }
}
