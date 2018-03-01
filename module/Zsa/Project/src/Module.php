<?php

namespace Zsa\Project;

use Doctrine\ORM\EntityManager;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 *
 * @package Zsa\Project
 */
class Module implements Feature\ConfigProviderInterface, Feature\BootstrapListenerInterface
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

    /**
     * @param EventInterface|MvcEvent $e
     *
     * @return void
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $e->getApplication()->getServiceManager()->get(EntityManager::class);

        if ($entityManager->getFilters()->has('project_ignore_status')) {
            $entityManager->getFilters()->enable('project_ignore_status');
        }
    }
}
