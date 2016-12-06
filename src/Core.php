<?php
namespace Peak;

use Peak\Config;
use Peak\Exception;

/**
 * Peak Core
 */

class Core
{
    /**
     * Constants
     */
    const VERSION  = '2.0.0';
    const NAME     = 'PEAK';
    const DESCR    = 'PHP Elegant Application Kernel';

    /**
     * Current Environment
     * @final
     * @var string
     */
    private static $_env;

    /**
     * object itself
     * @var object
     */
    private static $_instance = null; 
    
    /**
     * Singleton peak core
     *
     * @return  object instance
     */
    public static function getInstance()
  	{
  		if (is_null(self::$_instance)) self::$_instance = new self();
  		return self::$_instance;
  	}

	/**
	 * Activate error_reporting based on app env
	 */
    private function __construct()
    {
        if(self::getEnv() === 'dev') {
        	//ini_set('error_reporting', (version_compare(PHP_VERSION, '5.3.0', '<') ? E_ALL|E_STRICT : E_ALL|E_DEPRECATED));
        	//faster...?
        	ini_set('error_reporting', (!function_exists('class_alias')) ? E_ALL|E_STRICT : E_ALL|E_DEPRECATED);
        }
    }
}