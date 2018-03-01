<?php

namespace Zsa\Program\Filter\Factory;

use Axmit\UserCore\Service\UserService;
use Psr\Container\ContainerInterface;
use Zsa\Program\Filter\ProgramFilter;

/**
 * Class ProgramFilterFactory
 *
 * @package Zsa\Program\Filter\Factory
 */
class ProgramFilterFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProgramFilter
     */
    public function __invoke(ContainerInterface $container)
    {
        $userService = $container->get(UserService::class);

        return new ProgramFilter($userService);
    }
}