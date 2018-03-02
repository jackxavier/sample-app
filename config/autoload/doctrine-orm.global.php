<?php
return [
    'doctrine' => [
        'connection' => [
            // default connection name
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => [
                    'host'          => 'localhost',
                    'port'          => '3306',
                    'user'          => 'root',
                    'password'      => 'pasd123',
                    'dbname'        => 'axmitzf',
                    'charset'       => 'utf8',
                    'driverOptions' => [1002 => 'SET NAMES utf8'],
                ],
            ],
        ],
    ],
];