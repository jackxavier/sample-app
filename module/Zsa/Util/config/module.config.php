<?php

namespace Zsa\Util;

use Zsa\Util\Hydrator\PropertyHydrator;
use Zsa\Util\Validator\BaseValueTypeValidator;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'validators'      => [
        'factories' => [
            BaseValueTypeValidator::class => InvokableFactory::class,
        ],
    ],
    'hydrators'       => [
        'factories' => [
            PropertyHydrator::class => InvokableFactory::class,
        ],
    ],
];