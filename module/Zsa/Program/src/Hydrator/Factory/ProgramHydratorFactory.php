<?php

namespace Zsa\Program\Hydrator\Factory;

use Zend\Hydrator\NamingStrategy\MapNamingStrategy;
use Zsa\Program\Hydrator\ProgramHydrator;

/**
 * Class ProgramHydratorFactory
 *
 * @package Zsa\Program\Hydrator\Factory
 */
class ProgramHydratorFactory
{
    /**
     * @return ProgramHydrator
     */
    public function __invoke()
    {
        $hydrator = new ProgramHydrator();

        $namingStrategy = new MapNamingStrategy(['user' => 'controller']);
        $hydrator->setNamingStrategy($namingStrategy);
        $hydrator->setUnderscoreSeparatedKeys(true);

        return $hydrator;
    }
}
