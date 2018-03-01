<?php
return [
    'db'           => [
        'adapters' => [
            'AxmitZF3' => [
                'database' => 'axmitzf',
                'driver'   => 'PDO_Mysql',
                'username' => 'root',
                'password' => 'pasd123',
            ],
        ],
    ],
    'view_manager' => [
        'display_exceptions' => true,
    ],
    'router'       => [
        'routes' => [],
    ],
];
