<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Axmit\Monolog\Logger\AppLogger\AppLogger;
use Axmit\Monolog\Logger\Listener\ExceptionHandler;
use Axmit\Util\Logger\AbstractLoggerFactory;
use Monolog\ErrorHandler;
use Psr\Log\LogLevel;
use Zend\Console\Console;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 *
 * @package Application
 */
class Module implements Feature\ConfigProviderInterface, Feature\BootstrapListenerInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface|MvcEvent $e
     *
     * @return void
     */
    public function onBootstrap(EventInterface $e)
    {
        $app      = $e->getApplication();
        $services = $app->getServiceManager();
        $events   = $app->getEventManager();

        if (!Console::isConsole()) {
            /** @var AppLogger $logger */
            $logger = $services->build(
                AbstractLoggerFactory::assembleNameForChannel('error'), ['level' => LogLevel::ERROR]
            );
            /** @var ExceptionHandler $exceptionHandler */
            $exceptionHandler = $services->get(ExceptionHandler::class);
            $exceptionHandler->setLogger($logger);
            $exceptionHandler->attach($events);

            ErrorHandler::register($logger);
        }


    }
}
