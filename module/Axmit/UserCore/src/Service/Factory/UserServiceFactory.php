<?php

namespace Axmit\UserCore\Service\Factory;

use Axmit\UserCore\Dao\Doctrine\UserDoctrineDao;
use Axmit\UserCore\Service\UserService;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserServiceFactory
 *
 * @package Axmit\UserCore\Service\Factory
 */
class UserServiceFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserDoctrineDao $userDao */
        $userDao = $container->get(UserDoctrineDao::class);

        return new UserService($userDao);
    }
}