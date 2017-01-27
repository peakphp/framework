<?php

/**
 * CONSTANT(S)
 */
if(!defined('PEAK_VERSION'))
    define('PEAK_VERSION', '2.0.0');

/**
 * relativeBasepath()
 */
if(!function_exists('relativeBasepath')) {
    /**
     * Get relativepath of specified dir from the server document root
     * 
     * @param  string $dir
     * @return string     
     */
    function relativeBasepath($dir, $doc_root = null) {
        if(!isset($doc_root)) {
            $doc_root = (!isset($_SERVER['DOCUMENT_ROOT'])) ? '' : $_SERVER['DOCUMENT_ROOT'];
        }
        return substr(str_replace([$doc_root,basename($dir)],'',str_replace('\\','/',$dir)), 0, -1);
    }
}

/**
 * relativePath()
 */
if(!function_exists('relativePath')) {
    /**
     * Get relative path of specified dir from the server document root
     * 
     * @param  string $dir
     * @return string     
     */
    function relativePath($dir, $doc_root = null) {
        if(!isset($doc_root)) {
            $doc_root = (!isset($_SERVER['DOCUMENT_ROOT'])) ? '' : $_SERVER['DOCUMENT_ROOT'];
        }
        return str_replace([$doc_root,$dir],'',str_replace('\\','/',$dir));
    }
}

/**
 * __()
 */
if(!function_exists('__')) {
    /**
     * Shortcut of Peak\Lang::__()
     * 
     * @param  string         $text     
     * @param  array|string   $replaces 
     * @param  closure        $func     
     * @return string          
     */
    function __($text, $replaces = null, $func = null) {
        return \Peak\Lang::__($text, $replaces, $func);
    }
}


/**
 * _e()
 */
if(!function_exists('_e')) {
    /**
     * Shortcut of Peak\Lang::_e()
     * 
     * @param  string         $text     
     * @param  array|string   $replaces 
     * @param  closure        $func     
     * @return string          
     */
    function _e($text, $replaces = null, $func = null) { 
        \Peak\Lang::_e($text, $replaces, $func); 
    }
}

/**
 * phpinput()
 */
if(!function_exists('phpinput')) {
    /**
     * Retreive a collection object from php://input 
     */
    function phpinput() { 

        $raw  = file_get_contents('php://input');
        $post = json_decode($raw , true); // for json input

        // incase json post is empty but $_POST is not we will use it
        if(!empty($raw) && empty($post) && isset($_POST)) {
            $post = $_POST;
        }

        return \Peak\Collection::make($post);
    }
}

/**
 * is_env()
 */
if(!function_exists('isEnv')) {
    /**
     * shorcut for APPLICATION_ENV === $env verification
     */
    function isEnv($env) { 

        if(defined('APPLICATION_ENV')) {
            return (APPLICATION_ENV === $env);
        }
        return false;
    }
}

/**
 * is_env()
 */
if(!function_exists('isCli')) {
    /**
     * Detect if command line internface
     */
    function isCli() { 
        return (php_sapi_name() === 'cli' OR defined('STDIN'));
    }
}

/**
 * config()
 */
if(!function_exists('config')) {
    /**
     * shorcut for \Peak\Application::conf([$path, $value])
     */
    function config($path = null, $value = null) { 

        return \Peak\Application::conf($path, $value);
    }
}


/**
 * collection()
 */
if(!function_exists('collection')) {
    /**
     * shorcut for \Peak\Collection::make([$items])
     */
    function collection($items = null) { 

        return \Peak\Collection::make($items);
    }
}