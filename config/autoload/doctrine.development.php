<?php
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => [
                    'path' => APPLICATION_PATH . '/test/testing.db',
                ],
            ],
        ],
        'driver' => [
            'test_annotation_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [APPLICATION_PATH . '/test/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    'DiCommonTest\Test\Entity' => 'test_annotation_driver',
                ],
            ],
        ],
    ],
];
