<?php

/*
|--------------------------------------------------------------------------
| Peak constant(s)
|--------------------------------------------------------------------------
*/
if(!defined('PEAK_VERSION'))
    define('PEAK_VERSION', '2.0.10');

/*
|--------------------------------------------------------------------------
| General related functions
|--------------------------------------------------------------------------
*/
   
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
 * isCli()
 */
if(!function_exists('isCli')) {
    /**
     * Detect if we are in command line interface mode
     */
    function isCli() { 
        return (php_sapi_name() === 'cli' OR defined('STDIN'));
    }
}

/**
 * showAllErrors()
 */
if(!function_exists('showAllErrors')) {
    /**
     * Try to force the display of all errors
     */
    function showAllErrors() { 
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
}


/*
|--------------------------------------------------------------------------
| Peak application related functions 
|--------------------------------------------------------------------------
*/

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
 * isEnv()
 */
if(!function_exists('isEnv')) {
    /**
     * Check is env match curretn application env
     * 
     * @param  string  $env
     * @return boolean     
     */
    function isEnv($env) { 

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
     * App configuration
     * 
     * @param  string|null $path
     * @param  mixed|null  $value
     * @return mixed    
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
     * Create a new Collection
     * 
     * @param  array|null $items 
     * @return \Peak\Collection     
     */
    function collection($items = null) { 

        return \Peak\Collection::make($items);
    }
}

/**
 * session()
 */
if(!function_exists('session')) {
    /**
     * Create/Access to session collection
     * 
     * @param  array|null $items 
     * @return \Peak\session     
     */
    function session($path = null, $value = null) { 

        $s = \Peak\Registry::o()->session;
        if(!isset($s)) {
            $s = \Peak\Registry::set('session', new \Peak\Config\Session());
        }

        if(!isset($path) && !isset($value)) {
            return $s;
        }
        elseif(isset($path) && !isset($value)) {
            return $s->get($path);
        }
        
        return $s->set($path, $value);
    }
}

/**
 * url()
 */
if(!function_exists('url')) {
    /**
     * Generate application url
     */
    function url($path = null, $use_forwarded_host = true) { 

        $s = $_SERVER;

        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

        $final    = '//' . str_ireplace('//','/', $host.relativePath(config('path.public')).'/'.$path);

        return $final;
    }
}
