<?php

namespace Zsa\Pip\Dao\Factory;

use Doctrine\ORM\EntityManager;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;
use Zsa\Pip\Dao\Doctrine\PipHistoryRegistryDoctrineDao;
use Zsa\Pip\Entity\PipHistoryRegistry;
use Psr\Container\ContainerInterface;

/**
 * Class PipHistoryRegistryDaoFactory
 *
 * @package Zsa\Pip\Dao\Factory
 */
class PipHistoryRegistryDaoFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PipHistoryRegistryDoctrineDao
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $objectManager */
        $objectManager  = $container->get(EntityManager::class);
        $entityClass    = PipHistoryRegistry::class;
        $specifications = new DoctrineSpecificationManager();

        return new PipHistoryRegistryDoctrineDao($objectManager, $entityClass, $specifications);
    }
}