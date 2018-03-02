<?php

namespace Api\Project\V1\Rpc\ProjectTasks;

use Doctrine\ORM\EntityManager;
use Epos\UserCore\Service\UserService;
use Zsa\Project\Service\ProjectService;
use Zsa\Task\Filter\TaskFilter;
use Zsa\Task\Service\TaskService;
use Interop\Container\ContainerInterface;
use Mmd\Assets\Map\Filter\FilterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProjectTasksControllerFactory
 *
 * @package Api\Project\V1\Rpc\ProjectTasks
 */
class ProjectTasksControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ProjectTasksController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserService $userService */
        $userService = $container->get(UserService::class);
        /** @var ProjectService $projectService */
        $projectService = $container->get(ProjectService::class);
        /** @var TaskService $taskService */
        $taskService = $container->get(TaskService::class);
        /** @var ContainerInterface $formElementManager */
        $formElementManager = $container->get('FormElementManager');
        /** @var FilterInterface|TaskFilter $converter */
        $converter = $container->get(TaskFilter::class);
        $converter->setMode(TaskFilter::VALUE_MODE_VIEW);
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        $entityManager->getFilters()->disable('project_ignore_status');
        $entityManager->getFilters()->disable('protocol_ignore_status');

        return new ProjectTasksController($userService, $projectService, $taskService, $formElementManager, $converter);
    }
}
