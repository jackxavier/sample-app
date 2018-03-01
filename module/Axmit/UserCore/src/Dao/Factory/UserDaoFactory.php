<?php

namespace Axmit\UserCore\Dao\Factory;

use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;
use Axmit\UserCore\Dao\Doctrine\UserDoctrineDao;
use Axmit\UserCore\Entity\User;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserDaoFactory
 *
 * @package Axmit\UserCore\Dao\Factory
 */
class UserDaoFactory implements FactoryInterface
{
    /**
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
        /** @var EntityManager $objectManager */
        $objectManager  = $container->get(EntityManager::class);
        $className      = User::class;
        $specifications = new DoctrineSpecificationManager();

        return new UserDoctrineDao($objectManager, $className, $specifications);
    }
}