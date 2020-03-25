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
                'paths' => [
                    APPLICATION_PATH . '/test/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'DealerInspire\LaminasTestcase\Test\Entity' => 'test_annotation_driver',
                    'ZF\OAuth2\Doctrine\Entity' => 'oauth2_driver',
                ],
            ],
            'oauth2_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => [
                    APPLICATION_PATH . '/vendor/api-skeletons/zf-oauth2-doctrine/config/orm',
                    APPLICATION_PATH . '/vendor/api-skeletons/zf-oauth2-doctrine/test/asset/module/Doctrine/config/orm'
                ],
            ],
        ],
    ],
];
