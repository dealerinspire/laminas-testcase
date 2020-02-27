<?php
return [
    'modules' => [],
    'service_manager' => [],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => [
                    'path' => __DIR__ . '/../test/testing.db',
                ],
            ],
        ],
    ],
];
