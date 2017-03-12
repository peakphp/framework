<?php

/*
|--------------------------------------------------------------------------
| Peak application related functions 
|--------------------------------------------------------------------------
*/

/**
 * __()
 */
if (!function_exists('__')) {
    /**
     * Shortcut of Peak\Lang::__()
     *
     * @param  string         $text
     * @param  array|string   $replaces
     * @param  closure        $func
     * @return string
     */
    function __($text, $replaces = null, $func = null)
    {
        return \Peak\Lang::__($text, $replaces, $func);
    }
}

/**
 * _e()
 */
if (!function_exists('_e')) {
    /**
     * Shortcut of Peak\Lang::_e()
     *
     * @param  string         $text
     * @param  array|string   $replaces
     * @param  closure        $func
     * @return string
     */
    function _e($text, $replaces = null, $func = null)
    {
        \Peak\Lang::_e($text, $replaces, $func);
    }
}

/**
 * isEnv()
 */
if (!function_exists('isEnv')) {
    /**
     * Check is env match curretn application env
     *
     * @param  string  $env
     * @return boolean
     */
    function isEnv($env)
    {
        if (defined('APPLICATION_ENV')) {
            return (APPLICATION_ENV === $env);
        }
        return false;
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
        return \Peak\Bedrock\Application::conf($path, $value);
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
        $container = \Peak\Bedrock\Application::container();
        $sess = $container->getInstance('Peak\Config\Session');

        if (!$container->hasInstance('Peak\Config\Session')) {
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
        $s = $_SERVER;

        $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp       = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (( $ssl ) ? 's' : '');
        $port     = $s['SERVER_PORT'];
        $port     = ((!$ssl && $port == '80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host     = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;

        $final    = '//' . str_ireplace('//','/', $host.relativePath(config('path.public')).'/'.$path);

        return $final;
    }
}
