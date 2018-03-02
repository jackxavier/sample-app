<?php

namespace Zsa\Project\Dao\Factory;

use Doctrine\ORM\EntityManager;
use Zsa\Project\Dao\Criterion\ProjectSpecifications;
use Zsa\Project\Dao\Doctrine\ProjectDoctrineDao;
use Zsa\Project\Entity\Project;
use Interop\Container\ContainerInterface;

/**
 * Class ProjectDaoFactory
 *
 * @package Zsa\Project\Dao\Factory
 */
class ProjectDaoFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProjectDoctrineDao
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $objectManager */
        $objectManager  = $container->get(EntityManager::class);
        $className      = Project::class;
        $specifications = $container->get(ProjectSpecifications::class);

        return new ProjectDoctrineDao($objectManager, $className, $specifications);
    }
}