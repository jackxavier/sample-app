<?php

namespace Zsa\Pip;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zsa\Pip\Dao\Criterion\Factory\PipDoctrineSpecificationFactory;
use Zsa\Pip\Dao\Criterion\PipDoctrineSpecification;
use Zsa\Pip\Dao\Doctrine\PipAssigneeDoctrineDao;
use Zsa\Pip\Dao\Doctrine\PipDoctrineDao;
use Zsa\Pip\Dao\Doctrine\PipHistoryRegistryDoctrineDao;
use Zsa\Pip\Dao\Factory\PipAssigneeDaoFactory;
use Zsa\Pip\Dao\Factory\PipDaoFactory;
use Zsa\Pip\Dao\Factory\PipHistoryRegistryDaoFactory;
use Zsa\Pip\Dao\PipAssigneeDaoInterface;
use Zsa\Pip\Dao\PipDaoInterface;
use Zsa\Pip\Dao\PipHistoryRegistryDaoInterface;
use Zsa\Pip\Dispatcher\Factory\PipAttachDispatcherFactory;
use Zsa\Pip\Dispatcher\PipAttachDispatcher;
use Zsa\Pip\Filter\PipFilter;
use Zsa\Pip\Form\Factory\PipAssignFormFactory;
use Zsa\Pip\Form\Factory\PipEditFormFactory;
use Zsa\Pip\Form\PipAssignForm;
use Zsa\Pip\Form\PipEditForm;
use Zsa\Pip\InputFilter\Factory\PipEditFilterFactory;
use Zsa\Pip\InputFilter\PipAssignFilter;
use Zsa\Pip\InputFilter\PipEditFilter;
use Zsa\Pip\Service\Factory\PipManagementServiceFactory;
use Zsa\Pip\Service\Factory\PipSelfAttachServiceFactory;
use Zsa\Pip\Service\Factory\PipServiceFactory;
use Zsa\Pip\Service\PipManagementService;
use Zsa\Pip\Service\PipSelfAttachService;
use Zsa\Pip\Service\PipService;

return [
    'service_manager' => [
        'aliases'   => [
            PipDaoInterface::class                => PipDoctrineDao::class,
            PipAssigneeDaoInterface::class        => PipAssigneeDoctrineDao::class,
            PipHistoryRegistryDaoInterface::class => PipHistoryRegistryDoctrineDao::class,
            'hid.service.pip'                     => PipSelfAttachService::class,
        ],
        'factories' => [
            PipDoctrineDao::class                => PipDaoFactory::class,
            PipAssigneeDoctrineDao::class        => PipAssigneeDaoFactory::class,
            PipHistoryRegistryDoctrineDao::class => PipHistoryRegistryDaoFactory::class,
            PipService::class                    => PipServiceFactory::class,
            PipFilter::class                     => InvokableFactory::class,
            PipDoctrineSpecification::class      => PipDoctrineSpecificationFactory::class,
            PipAttachDispatcher::class           => PipAttachDispatcherFactory::class,
            PipManagementService::class          => PipManagementServiceFactory::class,
            PipSelfAttachService::class          => PipSelfAttachServiceFactory::class,

        ],
    ],
    'input_filters'   => [
        'factories' => [
            PipEditFilter::class   => PipEditFilterFactory::class,
            PipAssignFilter::class => InvokableFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            PipEditForm::class   => PipEditFormFactory::class,
            PipAssignForm::class => PipAssignFormFactory::class,
        ],
    ],
    'doctrine'        => [
        'driver' => [
            'pip_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],
            'orm_default'  => [
                'drivers' => [
                    'Zsa\Pip\Entity' => 'pip_entities',
                ],
            ],
        ],
    ],
];
