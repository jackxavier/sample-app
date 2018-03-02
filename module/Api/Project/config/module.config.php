<?php

namespace Api\Project;

use Api\Project\V1\Rest\Project\Controller\ProjectController;
use Api\Project\V1\Rest\Project\ProjectResource;
use Api\Project\V1\Rest\Project\ProjectResourceFactory;
use Api\Project\V1\Rpc\GetReport\ProjectReportController;
use Api\Project\V1\Rpc\GetReport\ProjectReportControllerFactory;
use Api\Project\V1\Rpc\ProjectTasks\ProjectTasksController;
use Api\Project\V1\Rpc\ProjectTasks\ProjectTasksControllerFactory;
use Api\Protocol\V1\Rest\Protocol\Controller\ProtocolController;
use Axmit\Util\Controller\Factory\LoggingRestControllerFactory;
use Zsa\Project\Dto\Project\ProjectTo;
use Zsa\Project\Dto\Project\ProjectViewTo;
use Zsa\Util\Hydrator\PropertyHydrator;
use Zend\Router\Http\Segment;

return [
    'router'                 => [
        'routes' => [
            'api-project.rest.project'       => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/project[/:id]',
                    'defaults' => [
                        'controller' => ProjectController::class,
                    ],
                ],
            ],
            'api-project.rpc.project-tasks'  => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/project/:project_id/task',
                    'defaults' => [
                        'controller' => ProjectTasksController::class,
                        'action'     => 'projectTasks',
                    ],
                ],
            ],
            'api-project.rpc.project-report' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/project/:project_id/report',
                    'defaults' => [
                        'controller' => ProjectReportController::class,
                        'action'     => 'projectReport',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning'          => [
        'uri' => [
            0 => 'api-project.rest.project',
            1 => 'api-project.rpc.project-tasks',
            2 => 'api-project.rpc.project-report',
        ],
    ],
    'zf-rest'                => [
        ProjectController::class => [
            'listener'                   => ProjectResource::class,
            'route_name'                 => 'api-project.rest.project',
            'route_identifier_name'      => 'id',
            'collection_name'            => 'project',
            'controller_class'           => ProjectController::class,
            'entity_http_methods'        => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PATCH',
                3 => 'DELETE',
                4 => 'PUT',
            ],
            'collection_http_methods'    => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => ['hasProgram', 'title', 'controller', 'employee'],
            'page_size'                  => 25,
            'page_size_param'            => null,
            'entity_class'               => ProjectViewTo::class,
            'service_name'               => 'Project',
        ],
    ],
    'zf-rpc'                 => [
        ProjectTasksController::class => [
            'service_name' => 'ProjectTasks',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name'   => 'api-project.rpc.project-tasks',
        ],
        ProjectTasksController::class => [
            'service_name' => 'Projectreport',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name'   => 'api-project.rpc.project-report',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            ProjectController::class       => 'HalJson',
            ProjectTasksController::class  => 'HalJson',
            ProjectReportController::class => 'HaLJson',
        ],
        'accept_whitelist'       => [
            ProjectController::class      => [
                0 => 'application/vnd.api\\project.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            ProjectTasksController::class => [
                0 => 'application/vnd.api\\project-tasks.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            ProjectController::class       => [
                0 => 'application/vnd.api\\project.v1+json',
                1 => 'application/json',
            ],
            ProjectTasksController::class  => [
                0 => 'application/vnd.api\\project-tasks.v1+json',
                1 => 'application/json',
            ],
            ProjectReportController::class => [
                0 => 'application/vnd.api\\project-tasks.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal'                 => [
        'metadata_map' => [
            ProjectViewTo::class => [
                'hydrator'               => PropertyHydrator::class,
                'route_name'             => 'api-project.rest.project',
                'route_identifier_name'  => 'id',
                'entity_identifier_name' => 'id',
            ],
            ProjectTo::class     => [
                'hydrator'               => PropertyHydrator::class,
                'route_name'             => 'api-project.rest.project',
                'route_identifier_name'  => 'id',
                'entity_identifier_name' => 'id',
            ],
        ],
    ],
    'zf-mvc-auth'            => [
        'authorization' => [
            ProjectController::class       => [
                'collection' => [
                    'GET'    => false,
                    'POST'   => false,
                    'PUT'    => false,
                    'PATCH'  => false,
                    'DELETE' => false,
                ],
                'entity'     => [
                    'GET'    => false,
                    'POST'   => false,
                    'PUT'    => false,
                    'PATCH'  => false,
                    'DELETE' => false,
                ],
            ],
            ProjectTasksController::class  => [
                'entity' => [
                    'GET'    => false,
                    'POST'   => true,
                    'PUT'    => true,
                    'PATCH'  => true,
                    'DELETE' => true,
                ],
            ],
            ProjectReportController::class => [
                'entity' => [
                    'GET'    => false,
                    'POST'   => true,
                    'PUT'    => true,
                    'PATCH'  => true,
                    'DELETE' => true,
                ],
            ],
        ],
    ],
    'service_manager'        => [
        'factories' => [
            ProjectResource::class => ProjectResourceFactory::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            ProtocolController::class      => LoggingRestControllerFactory::class,
            ProjectTasksController::class  => ProjectTasksControllerFactory::class,
            ProjectReportController::class => ProjectReportControllerFactory::class,
        ],
    ],
];
