<?php

namespace Zsa\Project;

use Zsa\Project\Dao\Criterion\Factory\ProjectSpecificationsFactory;
use Zsa\Project\Dao\Criterion\ProjectSpecifications;
use Zsa\Project\Dao\Doctrine\ProjectAttendeeDoctrineDao;
use Zsa\Project\Dao\Doctrine\ProjectDoctrineDao;
use Zsa\Project\Dao\Factory\ProjectAttendeeDaoFactory;
use Zsa\Project\Dao\Factory\ProjectDaoFactory;
use Zsa\Project\Dao\Filter\ProjectIgnoreStatusFilter;
use Zsa\Project\Dao\Hydrator\Factory\ProjectFilteringHydratorFactory;
use Zsa\Project\Dao\Hydrator\ProjectFilteringHydrator;
use Zsa\Project\Dao\ProjectAttendeeDaoInterface;
use Zsa\Project\Dao\ProjectDaoInterface;
use Zsa\Project\Filter\ProjectFilter;
use Zsa\Project\Form\Factory\ProjectFormFactory;
use Zsa\Project\Form\ProjectForm;
use Zsa\Project\Hydrator\Factory\ProjectHydratorFactory;
use Zsa\Project\Hydrator\ProjectHydrator;
use Zsa\Project\InputFilter\ProjectEditFilter;
use Zsa\Project\Service\Factory\ProjectServiceFactory;
use Zsa\Project\Service\ProjectService;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'aliases'   => [
            ProjectDaoInterface::class         => ProjectDoctrineDao::class,
            ProjectAttendeeDaoInterface::class => ProjectAttendeeDoctrineDao::class,
            'hid.service.project'              => ProjectService::class,
        ],
        'factories' => [
            ProjectAttendeeDoctrineDao::class => ProjectAttendeeDaoFactory::class,
            ProjectDoctrineDao::class         => ProjectDaoFactory::class,
            ProjectService::class             => ProjectServiceFactory::class,
            ProjectFilter::class              => InvokableFactory::class,
            ProjectFilteringHydrator::class   => ProjectFilteringHydratorFactory::class,
            ProjectSpecifications::class      => ProjectSpecificationsFactory::class,
        ],
    ],
    'hydrators'       => [
        'factories' => [
            ProjectHydrator::class => ProjectHydratorFactory::class,
        ],
    ],
    'input_filters'   => [
        'factories' => [
            ProjectEditFilter::class => InvokableFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            ProjectForm::class => ProjectFormFactory::class,
        ],
    ],
    'doctrine'        => [
        'driver'        => [
            'project_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],
            'orm_default'      => [
                'drivers' => [
                    'Zsa\Project\Entity' => 'project_entities',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'filters' => [
                    'project_ignore_status' => ProjectIgnoreStatusFilter::class,
                ],
            ],
        ],
    ],
];