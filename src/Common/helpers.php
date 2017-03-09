<?php

/*
|--------------------------------------------------------------------------
| General helper functions
|--------------------------------------------------------------------------
*/

/**
 * relativeBasepath()
 */
if (!function_exists('relativeBasepath')) {
    /**
     * Get relativepath of specified dir from the server document root
     *
     * @param  string $dir
     * @return string
     */
    function relativeBasepath($dir, $doc_root = null) 
    {
        if (!isset($doc_root)) {
            $doc_root = (!isset($_SERVER['DOCUMENT_ROOT'])) ? '' : $_SERVER['DOCUMENT_ROOT'];
        }
        return substr(str_replace([$doc_root, basename($dir)], '', str_replace('\\', '/', $dir)), 0, -1);
    }
}

/**
 * relativePath()
 */
if (!function_exists('relativePath')) {
    /**
     * Get relative path of specified dir from the server document root
     *
     * @param  string $dir
     * @return string
     */
    function relativePath($dir, $doc_root = null) 
    {
        if (!isset($doc_root)) {
            $doc_root = (!isset($_SERVER['DOCUMENT_ROOT'])) ? '' : $_SERVER['DOCUMENT_ROOT'];
        }
        return str_replace([$doc_root, $dir], '', str_replace('\\', '/', $dir));
    }
}

/**
 * isCli()
 */
if (!function_exists('isCli')) {
    /**
     * Detect if we are in command line interface mode
     */
    function isCli()
    {
        return (php_sapi_name() === 'cli' || defined('STDIN'));
    }
}

/**
 * showAllErrors()
 */
if (!function_exists('showAllErrors')) {
    /**
     * Try to force the display of all errors
     */
    function showAllErrors()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
}