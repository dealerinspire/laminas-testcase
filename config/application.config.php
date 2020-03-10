<?php
return [
    'modules' => [],
    'service_manager' => [],
    'module_listener_options' => [
        'module_paths' => ['./vendor'],
        'config_glob_paths' => [
            sprintf(
                '%s/config/autoload/{,*.}{global,%s,local}.php',
                APPLICATION_PATH,
                APPLICATION_ENV
            ),
        ],
    ],
];
