<?php

namespace Zsa\Program\Dao\Factory;

use Doctrine\ORM\EntityManager;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;
use Zsa\Program\Dao\Doctrine\ProgramDoctrineDao;
use Zsa\Program\Entity\Program;
use Interop\Container\ContainerInterface;

/**
 * Class ProgramDaoFactory
 *
 * @package Zsa\Program\Dao\Factory
 */
class ProgramDaoFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProgramDoctrineDao
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $objectManager */
        $objectManager  = $container->get(EntityManager::class);
        $entityClass    = Program::class;
        $specifications = new DoctrineSpecificationManager();

        return new ProgramDoctrineDao($objectManager, $entityClass, $specifications);
    }
}