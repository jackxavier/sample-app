<?php

namespace Zsa\Pip\Dao\Factory;

use Doctrine\ORM\EntityManager;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;
use Zsa\Pip\Dao\Doctrine\PipAssigneeDoctrineDao;
use Zsa\Pip\Entity\PipAssignee;
use Psr\Container\ContainerInterface;

/**
 * Class PipAssigneeDaoFactory
 *
 * @package Zsa\Pip\Dao\Factory
 */
class PipAssigneeDaoFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PipAssigneeDoctrineDao
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $objectManager */
        $objectManager  = $container->get(EntityManager::class);
        $entityClass    = PipAssignee::class;
        $specifications = new DoctrineSpecificationManager();

        return new PipAssigneeDoctrineDao($objectManager, $entityClass, $specifications);
    }
}