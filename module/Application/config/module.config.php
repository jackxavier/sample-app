<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\Factory\Plugin\ReturnUriFactory;
use Application\Controller\IndexController;
use Application\Controller\Plugin\ReturnUri;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router'             => [
        'routes' => [
            'home' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/[:locale[/]]',
                    'defaults'    => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'locale' => '[a-z]{2,3}([-_][a-zA-Z]{2}|)',
                    ],
                ],
            ],
        ],
    ],
    'controllers'        => [
        'factories' => [
            IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager'       => [
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/index',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
        'controller_map'           => [
            IndexController::class => 'application/index',
        ],
        'strategies'               => [
            'ViewJsonStrategy',
        ],
    ],
    'controller_plugins' => [
        'aliases'   => [
            'returnUri' => ReturnUri::class,
        ],
        'factories' => [
            ReturnUri::class => ReturnUriFactory::class,
        ],
    ],
    'app.console'        => [
        'commands' => [],
    ],
];
