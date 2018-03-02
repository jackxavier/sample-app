<?php

namespace Zsa\Project\Dao\Criterion\Factory;

use Zsa\Project\Dao\Criterion\ProjectSpecifications;
use Zsa\Project\Dao\Criterion\Specification\ProjectAttendeeSpecification;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProjectSpecificationsFactory
 *
 * @package Zsa\Project\Dao\Criterion\Factory
 */
class ProjectSpecificationsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ProjectSpecifications
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $specifications = new ProjectSpecifications();
        $specifications->addSpecification(ProjectSpecifications::PROJECT_ATTENDEE, new ProjectAttendeeSpecification());

        return $specifications;
    }
}
