<?php

namespace Zsa\Project;

use Axmit\UserCore\Dao\Doctrine\UserDoctrineDao;
use Axmit\UserCore\Dao\UserDaoInterface;
use Axmit\UserCore\Filter\UserFilter;
use Axmit\UserCore\Service\UserService;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'aliases'   => [
            UserDaoInterface::class => UserDoctrineDao::class,
        ],
        'factories' => [
            UserDoctrineDao::class => UserDoctrineDaoFactory::class,
            UserService::class     => UserServiceFactory::class,
            UserFilter::class      => InvokableFactory::class,
        ],
    ],
    'doctrine'        => [
        'driver' => [
            'user_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],
            'orm_default'   => [
                'drivers' => [
                    'Axmit\UserCore\Entity' => 'user_entities',
                ],
            ],
        ],
    ],
];