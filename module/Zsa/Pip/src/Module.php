<?php

namespace Zsa\Pip;

use Zend\ModuleManager\Feature;

/**
 * Class Module
 *
 * @package Zsa\Pip
 */
class Module implements Feature\ConfigProviderInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
