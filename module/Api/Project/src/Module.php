<?php

namespace Api\Project;

use ZF\Apigility\Provider\ApigilityProviderInterface;
use Zend\ModuleManager\Feature;

/**
 * Class Module
 *
 * @package Api\Project
 */
class Module implements ApigilityProviderInterface, Feature\ConfigProviderInterface
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

    public function getAutoloaderConfig()
    {
        return [
            'ZF\Apigility\Autoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }
}

