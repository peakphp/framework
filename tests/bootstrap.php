<?php

/*
|--------------------------------------------------------------------------
| Peak tests bootstrap
|--------------------------------------------------------------------------
*/

include __DIR__.'/../vendor/autoload.php';

use Peak\Bedrock\Application;
use Peak\Di\Container;

define('FIXTURES_PATH', __DIR__.'/fixtures');

/**
 * Create a dummy app
 */
if(!function_exists('dummyApp')) {
    function dummyApp($env = 'dev', $config = null) {

        $final = [
            'env'  => $env,
            'conf' => FIXTURES_PATH.'/app/config.php',
            'path' => [
                'public' => FIXTURES_PATH,
                'app'    => FIXTURES_PATH.'/app/',
            ]
        ];

        if (isset($config)) {
            $final = $config;
        }

        return new Application(new Container, $final);
    }
}