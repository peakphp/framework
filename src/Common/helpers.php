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

/**
 * collection()
 */
if (!function_exists('collection')) {
    /**
     * Create a new Collection
     *
     * @param  array|null $items
     * @return \Peak\Common\Collection
     */
    function collection($items = null)
    {
        return \Peak\Common\Collection::make($items);
    }
}

/**
 * phpinput()
 */
if (!function_exists('phpinput')) {
    /**
     * Retreive a collection object from php://input
     *
     * @param  Closure $closure
     * @return Peak\Common\Collection
     */
    function phpinput(Closure $closure = null)
    {
        $raw  = file_get_contents('php://input');
        $post = json_decode($raw, true); // for json input

        // in case json post is empty but $_POST is not we will use it
        if (!empty($raw) && empty($post) && filter_input_array(INPUT_POST)) {
            $post = filter_input_array(INPUT_POST);
        }

        $coll = \Peak\Common\Collection::make($post);

        if (isset($closure)) {
            $coll->map($closure);
        }

        return $coll;
    }
}

/**
 * exceptionTrace()
 */
if (!function_exists('exceptionTrace')) {
    /**
     * Retreive a more comprehensive exception debug backtrace
     *
     * @param  \Exception $exc
     */
    function exceptionTrace(\Exception $exc)
    {
        $trace = debug_backtrace();

        $msg = trim($exc->getMessage());

        $content = '['.date('Y-m-d H:i:s')."] ".get_class($exc)."\n";
        $content .= $msg."\n";

        if ($exc instanceof \Peak\Common\DataException) {
            $content .= $exc->dumpData();
            $content .= str_pad('', mb_strlen($exc->dumpData()), '-')."\n";
        } else {
            $content .= str_pad('', mb_strlen($msg), '-')."\n";
        }
        $content .= str_replace('#', "#", $exc->getTraceAsString());

        return $content;
    }
}

/**
 * printExceptionTrace()
 */
if (!function_exists('printExceptionTrace')) {
    /**
     * Print exceptionTrace
     * @see exceptionTrace()
     */
    function printExceptionTrace(\Exception $exc)
    {
        print_r(exceptionTrace($exc));
    }
}

/**
 * printHtmlExceptionTrace()
 */
if (!function_exists('printHtmlExceptionTrace')) {
    /**
     * Print exceptionTrace in html pre block
     * @see exceptionTrace()
     */
    function printHtmlExceptionTrace(\Exception $exc)
    {
        echo '<pre>';
        print_r(exceptionTrace($exc));
        echo '</pre>';
    }
}

/**
 * shortClassName()
 */
if (!function_exists('shortClassName')) {
    /**
     * Get class name of an object without the namespace
     */
    function shortClassName($obj)
    {
        return ((new \ReflectionClass($obj))->getShortName());
    }
}
