<?php

namespace Zsa\Program\Dao\Hydrator\Factory;

use Axmit\UserCore\Dao\UserDaoInterface;
use Zsa\Program\Dao\Hydrator\ProgramFilteringHydrator;
use Interop\Container\ContainerInterface;

/**
 * Class ProgramFilteringHydratorFactory
 *
 * @package Zsa\Program\Dao\Hydrator\Factory
 */
class ProgramFilteringHydratorFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProgramFilteringHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var UserDaoInterface $userDao */
        $userDao = $container->get(UserDaoInterface::class);

        return new ProgramFilteringHydrator($userDao);
    }
}