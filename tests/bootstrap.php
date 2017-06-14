<?php

/*
|--------------------------------------------------------------------------
| Peak tests bootstrap
|--------------------------------------------------------------------------
*/

include __DIR__.'/../vendor/autoload.php';

define('FIXTURES_PATH', __DIR__.'/fixtures');

/**
 * Create a dummy app
 */
if(!function_exists('dummyApp')) {
    function dummyApp($env = 'dev') {
        return new Peak\Bedrock\Application(new Peak\Di\Container, [
            'env'  => $env,
            'conf' => 'config.php',
            'path' => [
                'public' => FIXTURES_PATH,
                'app'    => FIXTURES_PATH.'/app/',
            ]
        ]);
    }
}