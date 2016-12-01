<?php
/**
 * Application configuration example in php instead of ini file
 * Faster to load but more complex to modify if we compare with app ini config file
 *
 * @version $Id$
 */
return array(
    'all' => array(
            'php' => array(
                    'display_errors' => 1,
                    'display_startup_errors' => 1,
                    'date' => array(
                            'timezone' => 'America/Montreal'
                        )
                ),

            'front' => array(
                    'allow_internal_controllers' => 1,
                    'default_controller' => 'index',
                    'error_controller' => 'error',
                ),

            'view' => array(
                    'engine' => 'Layouts',
                    'useLayout' => 'homepage',
                ),

        ),

    'development' => array(),
    'testing' => array(),
    'staging' => array(),

    'production' => array(
            'php' => array(
                    'display_errors' => 0,
                    'display_startup_errors' => 0
                ),
        ),
);