<?php

use ZF\Apigility\Admin\Model\ModulePathSpec;

return [
    'zf-apigility-admin' => [
        'path_spec' => ModulePathSpec::PSR_4,
    ],
    'zf-configuration'   => [
        'enable_short_array' => true,
        'class_name_scalars' => true,
    ],
    'view_manager'       => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
    ],
];