<?php
/**
 * Config file example
 */
return [
    'all' => [
        'php' => [
            'display_errors' => 1,
            'display_startup_errors' => 1,
            'date' => [
                'timezone' => 'America/Montreal'
            ]
        ],

        'front' => [
            'allow_internal_controllers' => 1,
            'default_controller' => 'index',
            'error_controller' => 'error',
        ],

        'view' => [
            'engine' => 'Layouts',
            'useLayout' => 'homepage',
        ],

    ],

    'dev' => [],
    'testing' => [],
    'staging' => [],

    'prod' => [
        'php' => [
            'display_errors' => 0,
            'display_startup_errors' => 0
        ],
    ],
];