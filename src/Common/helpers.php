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
     * Get relative path of specified dir from the server document root
     *
     * @param  string $dir
     * @return string
     */
    function relativeBasepath($dir, $doc_root = null)
    {
        if (!isset($doc_root)) {
            $doc_root = filter_var(getenv('DOCUMENT_ROOT'));
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
            $doc_root = filter_var(getenv('DOCUMENT_ROOT'));
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
     * Retrieve a collection object from php://input
     *
     * @param  Closure $closure
     * @return Peak\Common\PhpInput
     */
    function phpinput(Closure $closure = null)
    {
        $phpinput = new \Peak\Common\PhpInput();

        if (isset($closure)) {
            $phpinput->map($closure);
        }

        return $phpinput;
    }
}

/**
 * exceptionTrace()
 */
if (!function_exists('exceptionTrace')) {
    /**
     * Retrieve a more comprehensive exception debug backtrace
     *
     * @param  \Exception $exc
     */
    function exceptionTrace(\Exception $exc)
    {
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

/**
 * getClassFilePath()
 */
if (!function_exists('getClassFilePath')) {
    /**
     * Get class complete file path
     */
    function getClassFilePath($obj)
    {
        return (new \ReflectionClass($obj))->getFileName();
    }
}


/**
 * formatFileSize()
 */
if (!function_exists('formatFileSize')) {
    /**
     * Format size in bytes to a more appropriate / readable format
     */
    function formatFileSize($size)
    {
        if (empty($size)) {
            return '0 kB';
        }
        $unit = ['B','kB','MB','GB','TB','PB'];
        return round($size/pow(1024, ($i=floor(log($size, 1024)))), 2).' '.$unit[$i];
    }
}


/**
 * fileExpired()
 */
if (!function_exists('fileExpired')) {
    /**
     * Check if file is expired
     *
     * @param string $file
     * @param mixed $expiration_time expiration time, \Peak\Common\TimeExpression expression accepted
     * @return bool
     */
    function fileExpired($file, $expiration_time)
    {
        $expiration_time = (new \Peak\Common\TimeExpression($expiration_time))->toSeconds();
        $file_date = filemtime($file);
        $now = time();
        $delay = $now - $file_date;
        return ($delay >= $expiration_time) ? true : false;
    }
}
