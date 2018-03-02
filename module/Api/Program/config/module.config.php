<?php

namespace Api\Program;

use Api\Program\V1\Rest\Program\Controller\ProgramController;
use Api\Program\V1\Rest\Program\ProgramResource;
use Api\Program\V1\Rest\Program\ProgramResourceFactory;
use Zend\Router\Http\Segment;
use Zsa\Program\Dto\Program\ProgramTo;
use Zsa\Program\Dto\Program\ProgramViewTo;
use Zsa\Project\Dto\Project\ProjectCollection;
use Zsa\Util\Hydrator\PropertyHydrator;

return [
    'router'                 => [
        'routes' => [
            'api-program.rest.program' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/program[/:id]',
                    'defaults' => [
                        'controller' => ProgramController::class,
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning'          => [
        'uri' => [
            0 => 'api-program.rest.program',
        ],
    ],
    'zf-rest'                => [
        ProgramController::class => [
            'listener'                   => ProgramResource::class,
            'route_name'                 => 'api-program.rest.program',
            'route_identifier_name'      => 'id',
            'collection_name'            => 'program',
            'controller_class'           => ProgramController::class,
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
            'collection_query_whitelist' => ['title', 'controller'],
            'page_size'                  => 25,
            'page_size_param'            => null,
            'entity_class'               => ProgramViewTo::class,
            'service_name'               => 'Program',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            ProgramController::class => 'HalJson',
        ],
        'accept_whitelist'       => [
            ProgramController::class => [
                0 => 'application/vnd.api\\program.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            ProgramController::class => [
                0 => 'application/vnd.api\\program.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal'                 => [
        'metadata_map' => [
            ProgramViewTo::class     => [
                'hydrator'               => PropertyHydrator::class,
                'route_name'             => 'api-program.rest.program',
                'route_identifier_name'  => 'id',
                'entity_identifier_name' => 'id',
            ],
            ProgramTo::class         => [
                'hydrator'               => PropertyHydrator::class,
                'route_name'             => 'api-program.rest.program',
                'route_identifier_name'  => 'id',
                'entity_identifier_name' => 'id',
            ],
            ProjectCollection::class => [
                'entity_identifier_name' => 'id',
                'force_self_link'        => false,
                'is_collection'          => true,
            ],
        ],
    ],
    'zf-mvc-auth'            => [
        'authorization' => [
            ProgramController::class => [
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
        ],
    ],
    'service_manager'        => [
        'factories' => [
            ProgramResource::class => ProgramResourceFactory::class,
        ],
    ],
];
