<?php

namespace Zsa\Project\Hydrator\Factory;

use Zend\Hydrator\NamingStrategy\MapNamingStrategy;
use Zsa\Project\Hydrator\ProjectHydrator;

/**
 * Class ProjectHydratorFactory
 *
 * @package Zsa\Project\Hydrator\Factory
 */
class ProjectHydratorFactory
{
    /**
     * @return ProjectHydrator
     */
    public function __invoke()
    {
        $hydrator = new ProjectHydrator();

        $namingStrategy = new MapNamingStrategy(['user' => 'controller']);
        $hydrator->setNamingStrategy($namingStrategy);
        $hydrator->setUnderscoreSeparatedKeys(true);

        return $hydrator;
    }
}
