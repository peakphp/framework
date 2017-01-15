<?php

/**
 * CONSTANT(S)
 */
if(!defined('PEAK_VERSION'))
    define('PEAK_VERSION', '2.0.0');

/**
 * relative_basepath()
 */
if(!function_exists('relative_basepath')) {
    /**
     * Get relativepath of specified dir from the server document root
     * 
     * @param  string $dir
     * @return string     
     */
    function relative_basepath($dir, $doc_root = null) {
        if(!isset($doc_root)) {
            $doc_root = (!isset($_SERVER['DOCUMENT_ROOT'])) ? '' : $_SERVER['DOCUMENT_ROOT'];
        }
        return substr(str_replace([$doc_root,basename($dir)],'',str_replace('\\','/',$dir)), 0, -1);
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
if(!function_exists('is_env')) {
    /**
     * shorcut for APPLICATION_ENV === $env verification
     */
    function is_env($env) { 

        if(defined('APPLICATION_ENV')) {
            return (APPLICATION_ENV === $env);
        }
        return false;
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

