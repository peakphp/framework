<?php
/**
 * Config file example
 */
return [

    'php' => [
        'display_errors' => 1,
        'display_startup_errors' => 1,
        'date' => [
            'timezone' => 'America/Toronto'
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
        'set' => [
            'var1' => 'foo',
            'var2' => 'bar',
        ]
    ],
    'routes' => [ //custom routes ...
        [
            'route'      => 'user/{id}:num',
            'controller' => 'user',
            'action'     => 'profile'
        ],
        'login | index/login',
        'logout | index/logout'
    ],
];

