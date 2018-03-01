<?php

return [
    'doctrine' => [
        'connection'               => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => [
                    'host'          => env('DB_HOST', 'localhost'),
                    'port'          => env('DB_PORT', '3306'),
                    'user'          => env('DB_USER'),
                    'password'      => env('DB_PASS'),
                    'dbname'        => env('DB_NAME'),
                    'driverOptions' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                    ],
                ],
            ],
        ],
        'migrations_configuration' => [
            'orm_default' => [
                'name'      => 'AxmitZF3 Migrations',
                'table'     => 'doctrine_migrations',
                'directory' => 'data/migrations',
            ],
        ],
    ],
];
