<?php

/*
|--------------------------------------------------------------------------
| Peak application related functions 
|--------------------------------------------------------------------------
*/

use Peak\Bedrock\Application;
use Peak\Common\Language;

/**
 * __()
 */
if (!function_exists('__')) {
    /**
     * Shortcut for $lang->translate(..)
     *
     * @param  string         $text
     * @param  array|string   $replaces
     * @return string
     */
    function __($text, $replaces = null)
    {
        if (Application::container()->has(Language::class)) {
            $lang = Application::get(Language::class);
            $text = $lang->translate($text, $replaces);
        }
        return $text;
    }
}

/**
 * _e()
 */
if (!function_exists('_e')) {
    /**
     * Shortcut for echo $lang->translate(..)
     *
     * @param  string         $text
     * @param  array|string   $replaces
     */
    function _e($text, $replaces = null)
    {
        $text = __($text, $replaces);
        echo $text;
    }
}

/**
 * isEnv()
 */
if (!function_exists('isEnv')) {
    /**
     * Check is env match curretn application env
     *
     * @param  string|array  $env
     * @return boolean
     */
    function isEnv($env)
    {
        if (defined('APPLICATION_ENV')) {
            if (is_array($env)) {
                return (in_array(APPLICATION_ENV, $env));
            }
            return (APPLICATION_ENV === $env);
        }
        return false;
    }
}

/**
 * detectEnvFile()
 */
if (!function_exists('detectEnvFile')) {
    /**
     * Look for environment file (.prod, .testing and .staging)
     * and return deducted environment string
     *
     * @param  string $path
     * @return string
     */
    function detectEnvFile($path)
    {
        $env = 'dev';

        if (file_exists($path.'/.prod')) {
            $env = 'prod';
        } elseif (file_exists($path.'/.staging')) {
            $env = 'staging';
        } elseif (file_exists($path.'/.testing')) {
            $env = 'testing';
        }

        return $env;
    }
}

/**
 * config()
 */
if (!function_exists('config')) {
    /**
     * App configuration
     *
     * @param  string|null $path
     * @param  mixed|null  $value
     * @return mixed
     */
    function config($path = null, $value = null)
    {
        return Application::conf($path, $value);
    }
}

/**
 * session()
 */
if (!function_exists('session')) {
    /**
     * Create/Access to session collection
     *
     * @param  array|null $items
     * @return \Peak\session
     */
    function session($path = null, $value = null)
    {
        $container = Application::container();
        $sess = $container->get('Peak\Config\Session');

        if (!$container->has('Peak\Config\Session')) {
            $sess = $container->instantiateAndStore('Peak\Config\Session');
        }

        if (!isset($path) && !isset($value)) {
            return $sess;
        } elseif (isset($path) && !isset($value)) {
            return $sess->get($path);
        }
        
        return $sess->set($path, $value);
    }
}

/**
 * url()
 */
if (!function_exists('url')) {
    /**
     * Generate application url
     *
     * @return string
     */
    function url($path = null, $use_forwarded_host = true)
    {
        $s = filter_input_array(INPUT_SERVER);

        $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp       = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (( $ssl ) ? 's' : '');
        $port     = $s['SERVER_PORT'];
        $port     = ((!$ssl && $port == '80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host     = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;

        $final    = '//' . str_ireplace('//', '/', $host.relativePath(config('path.public')).'/'.$path);

        return $final;
    }
}

/**
 * getPhinxMigrateEnv()
 */
if (!function_exists('getPhinxMigrateEnv')) {
    /**
     * Get environment argument value with Phinx migration
     *
     * @return string
     */
    function getPhinxMigrateEnv()
    {
        global $argv;
        if (!isset($argv) && !isset($_SERVER['argv'])) {
            return 'prod';
        } elseif ((!isset($argv) && isset($_SERVER['argv']))) {
            $argv = $_SERVER['argv'];
        }
        foreach ($argv as $index => $arg) {
            if (in_array($arg, ['-e', '--environment']) && isset($argv[$index + 1])) {
                return $argv[$index + 1];
            }
        }
        return 'prod';
    }
}
