<?php

use Peak\Application;
use Peak\Registry;

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
    function relative_basepath($dir) {
        $sdr = (!isset($_SERVER['DOCUMENT_ROOT'])) ? '' : $_SERVER['DOCUMENT_ROOT'];
        return substr(str_replace([$sdr,basename($dir)],'',str_replace('\\','/',$dir)), 0, -1);
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