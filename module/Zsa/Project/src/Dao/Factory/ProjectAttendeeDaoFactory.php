<?php

namespace Zsa\Project\Dao\Factory;

use Doctrine\ORM\EntityManager;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;
use Zsa\Project\Dao\Doctrine\ProjectAttendeeDoctrineDao;
use Zsa\Project\Entity\ProjectAttendee;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProjectAttendeeDaoFactory
 *
 * @package Zsa\ProjectAttendee\Dao\Factory
 */
class ProjectAttendeeDaoFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProjectAttendeeDoctrineDao
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $objectManager */
        $objectManager  = $container->get(EntityManager::class);
        $className      = ProjectAttendee::class;
        $specifications = new DoctrineSpecificationManager();

        return new ProjectAttendeeDoctrineDao($objectManager, $className, $specifications);
    }
}