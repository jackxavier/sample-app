<?php

namespace Zsa\Project\Dao\Hydrator\Factory;

use Axmit\UserCore\Dao\UserDaoInterface;
use Interop\Container\ContainerInterface;
use Zsa\Project\Dao\Hydrator\ProjectFilteringHydrator;

/**
 * Class ProjectFilteringHydratorFactory
 *
 * @package Zsa\Project\Dao\Hydrator\Factory
 */
class ProjectFilteringHydratorFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProjectFilteringHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var UserDaoInterface $userDao */
        $userDao = $container->get(UserDaoInterface::class);

        return new ProjectFilteringHydrator($userDao);
    }
}