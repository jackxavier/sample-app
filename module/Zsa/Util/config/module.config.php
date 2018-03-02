<?php

namespace Zsa\Util;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zsa\Util\Hydrator\PropertyHydrator;

return [
    'hydrators' => [
        'factories' => [
            PropertyHydrator::class => InvokableFactory::class,
        ],
    ],
];