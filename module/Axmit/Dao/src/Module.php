<?php

namespace Axmit\Dao;

use Zend\ModuleManager\Feature;

/**
 * Class Module
 */
class Module implements Feature\ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
