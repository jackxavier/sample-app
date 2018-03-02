<?php

namespace Api\Project\V1\Rest\Project;

use Axmit\Monolog\Logger\AppLogger\AppLogger;
use Axmit\Util\Logger\AbstractLoggerFactory;
use Epos\UserCore\Service\UserService;
use Zsa\Project\Filter\ProjectFilter;
use Zsa\Project\Service\ProjectService;
use Interop\Container\ContainerInterface;

/**
 * Class ProjectResourceFactory
 *
 * @package Api\Project\V1\Rest\Project
 */
class ProjectResourceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProjectResource
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ProjectService $projectService */
        $projectService = $container->get(ProjectService::class);
        /** @var UserService $userService */
        $userService = $container->get(UserService::class);
        /** @var ProjectFilter $projectFilter */
        $projectFilter = $container->get(ProjectFilter::class);
        /** @var ContainerInterface $formContainer */
        $formContainer = $container->get('FormElementManager');
        /** @var AppLogger $appLogger */
        $appLogger = $container->get(AbstractLoggerFactory::assembleNameForChannel());

        return new ProjectResource($userService, $projectService, $formContainer, $projectFilter, $appLogger);
    }

}