<?php

namespace Zsa\Program;

use Zsa\Program\Dao\Doctrine\ProgramDoctrineDao;
use Zsa\Program\Dao\Factory\ProgramDaoFactory;
use Zsa\Program\Dao\Filter\ProgramIgnoreStatusFilter;
use Zsa\Program\Dao\Hydrator\Factory\ProgramFilteringHydratorFactory;
use Zsa\Program\Dao\Hydrator\ProgramFilteringHydrator;
use Zsa\Program\Dao\ProgramDaoInterface;
use Zsa\Program\Filter\Factory\ProgramFilterFactory;
use Zsa\Program\Filter\ProgramFilter;
use Zsa\Program\Form\Factory\ProgramEditFormFactory;
use Zsa\Program\Form\ProgramEditForm;
use Zsa\Program\Hydrator\Factory\ProgramHydratorFactory;
use Zsa\Program\Hydrator\ProgramHydrator;
use Zsa\Program\InputFilter\ProgramEditFilter;
use Zsa\Program\Service\Factory\ProgramServiceFactory;
use Zsa\Program\Service\ProgramService;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'aliases'   => [
            ProgramDaoInterface::class => ProgramDoctrineDao::class,
            'hid.service.program'      => ProgramService::class,
        ],
        'factories' => [
            ProgramDoctrineDao::class       => ProgramDaoFactory::class,
            ProgramService::class           => ProgramServiceFactory::class,
            ProgramFilter::class            => ProgramFilterFactory::class,
            ProgramFilteringHydrator::class => ProgramFilteringHydratorFactory::class,
        ],
    ],
    'hydrators'       => [
        'factories' => [
            ProgramHydrator::class => ProgramHydratorFactory::class,
        ],
    ],
    'input_filters'   => [
        'factories' => [
            ProgramEditFilter::class => InvokableFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            ProgramEditForm::class => ProgramEditFormFactory::class,
        ],
    ],
    'doctrine'        => [
        'driver' => [
            'program_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],
            'orm_default'      => [
                'drivers' => [
                    'Zsa\Program\Entity' => 'program_entities',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'filters' => [
                    'program_ignore_status' => ProgramIgnoreStatusFilter::class,
                ],
            ],
        ],
    ],
];
