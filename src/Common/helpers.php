<?php

declare(strict_types=1);

use Peak\Collection\Collection;
use Peak\Common\PhpInput;
use Peak\Common\TimeExpression;

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
     * @param string $dir
     * @param null|string $doc_root
     * @return string
     */
    function relativeBasepath(string $dir, string $doc_root = null)
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
     * @param string $dir
     * @param null|string $doc_root
     * @return string
     */
    function relativePath(string $dir, string $doc_root = null): string
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
     *
     * @return bool
     */
    function isCli(): bool
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
    function showAllErrors(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
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
     * @return PhpInput
     */
    function phpinput(Closure $closure = null): PhpInput
    {
        $phpinput = new PhpInput();

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
     * @param Exception $exc
     * @return string
     */
    function exceptionTrace(Exception $exc): string
    {
        $msg = trim($exc->getMessage());

        $content = '['.date('Y-m-d H:i:s')."] ".get_class($exc)."\n";
        $content .= $msg."\n";


        $content .= str_pad('', mb_strlen($msg), '-')."\n";
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
    function printExceptionTrace(Exception $exc): void
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
    function printHtmlExceptionTrace(Exception $exc): void
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
     *
     * @param mixed $obj
     * @return string
     */
    function shortClassName($obj): string
    {
        return ((new ReflectionClass($obj))->getShortName());
    }
}

/**
 * getClassFilePath()
 */
if (!function_exists('getClassFilePath')) {
    /**
     * Get class complete file path
     *
     * @param mixed $obj
     * @return string
     */
    function getClassFilePath($obj): string
    {
        return (new ReflectionClass($obj))->getFileName();
    }
}


/**
 * formatFileSize()
 */
if (!function_exists('formatSize')) {
    /**
     * Format size in bytes to a more appropriate / readable format
     *
     * @param integer $size
     * @return string
     */
    function formatSize(int $size): string
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
    function fileExpired(string $file, $expiration_time): bool
    {
        $expiration_time = (new TimeExpression($expiration_time))->toSeconds();
        $file_date = filemtime($file);
        $now = time();
        $delay = $now - $file_date;
        return ($delay >= $expiration_time) ? true : false;
    }
}

/**
 * catchOutput()
 */
if (!function_exists('catchOutput')) {
    /**
     * Catch output with OB Control
     *
     * @param Closure $closure
     * @return string
     */
    function catchOutput(Closure $closure)
    {
        ob_start();
        $closure();
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}

/**
 * interpolate()
 */
if (!function_exists('interpolate')) {

    /**
     * Interpolation of message. Based on psr-3 example
     *
     * @param string $message
     * @param array $context
     * @param Closure|null $fn
     * @return string
     */
    function interpolate(string $message, array $context = array(), Closure $fn = null): string
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                if (isset($fn)) {
                    $val = $fn($val);
                }
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
