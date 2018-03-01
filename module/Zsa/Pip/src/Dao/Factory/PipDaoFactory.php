<?php

namespace Zsa\Pip\Dao\Factory;

use Doctrine\ORM\EntityManager;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;
use Zsa\Pip\Dao\Criterion\PipDoctrineSpecification;
use Zsa\Pip\Dao\Doctrine\PipDoctrineDao;
use Zsa\Pip\Entity\Pip;
use Psr\Container\ContainerInterface;

/**
 * Class PipDaoFactory
 *
 * @package Zsa\Pip\Dao\Factory
 */
class PipDaoFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PipDoctrineDao
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $objectManager */
        $objectManager  = $container->get(EntityManager::class);
        $entityClass    = Pip::class;
        $specifications = $container->get(PipDoctrineSpecification::class);

        return new PipDoctrineDao($objectManager, $entityClass, $specifications);
    }
}