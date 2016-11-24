<?php
/**
 * Peak Core
 *
 * @author   Francois Lajoie
 * @version  $Id$ 
 */

define('PK_VERSION', '0.9.9');
define('PK_NAME'   , 'PEAK');
define('PK_DESCR'  , 'Php wEb Application Kernel');

//handle all uncaught exceptions (try/catch block missing)
set_exception_handler('pkexception');
function pkexception($e) { die('<b>Uncaught Exception</b>: '. $e->getMessage()); }

//php >= 5.3
if(function_exists('class_alias')) class_alias('Peak_Core', 'Peak', false);

class Peak_Core
{

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
        if(self::getEnv() === 'development') {
        	//ini_set('error_reporting', (version_compare(PHP_VERSION, '5.3.0', '<') ? E_ALL|E_STRICT : E_ALL|E_DEPRECATED));
        	//faster...?
        	ini_set('error_reporting', (!function_exists('class_alias')) ? E_ALL|E_STRICT : E_ALL|E_DEPRECATED);
        }
    }

    /**
     * Init application config
     *
     * @param string $file
     */
    public static function initConfig($file, $apppath)
    {    		
    	self::getInstance();

    	$env = self::getEnv();

		$filepath = $apppath.'/'.$file;
    	$filetype = pathinfo($file, PATHINFO_EXTENSION);
		
		//we try to solve possible problem(s) here only in dev mode instead of possibly raise an exception
		if($env === 'development') {
			$generic_filepath = LIBRARY_ABSPATH.'/Peak/Application/genericapp.ini';

			if($filetype !== 'genericapp') {
				//non-existing file
				if(!file_exists($filepath)) {
					$filepath = $generic_filepath;
					$filetype = 'genericapp';
				}
				//unsupported type
				else if(!in_array($filetype, array('php','ini', 'json', 'genericapp'))) {
					$filepath = $generic_filepath;
					$filetype = 'genericapp';
				}
			}
			else $filepath = $generic_filepath;
		}

    	//load configuration object according to the file extension
	   	switch($filetype) {

    		case 'php' :
    			$conf = new Peak_Config($filepath);
    			break;
			
			case 'ini' :
				if($env === 'development') {
					//if it fail, we will use genericapp.ini in dev mode only
					try { $conf = new Peak_Config_Ini($filepath, true);	}
					catch(Exception $e) { $conf = new Peak_Config_Ini($generic_filepath, true);	}
				}
				else $conf = new Peak_Config_Ini($filepath, true);
				break;

            case 'json' :
                $conf = new Peak_Config_Json($filepath, true);
                break;

			case 'genericapp' :
				if($env === 'development') {
					$conf = new Peak_Config_Ini($filepath, true);
					break;
				} //we don't break here if we are not in dev mode				
			
			default :
				throw new Peak_Exception('ERR_CONFIG_FILE');
				break;
    	}
    	
		
		//check if we got the configuration for current environment mode or at least section 'all'
		if((!isset($conf->$env)) && (!isset($conf->all))) {
			if($env !== 'development') {
				throw new Peak_Exception('ERR_CUSTOM', 'no general configurations and/or '.$env.' configurations');
			}
			//here we will use Peak/Application/genericapp.ini as temporary config for the lazy user when in DEVELOPMENT ENV
			//This allow to boot an app with an empty config file
			else $conf = new Peak_Config_Ini($generic_filepath, true);			
    	}
    	
    	//add APPLICATION_ABSPATH to path config array if exists
    	if(isset($conf->all['path'])) {
    		foreach($conf->all['path'] as $pathname => $path) {
    			$conf->all['path'][$pathname] = $apppath.'/'.$path;
    		}
    	}
    	
    	//add APPLICATION_ABSPATH to 'path' key value
    	if(is_array($conf->$env) && (array_key_exists('path', $conf->$env))) {
			$conf_env = &$conf->$env;
    		foreach($conf_env['path'] as $pathname => $path) {
    			$conf_env['path'][$pathname] = $apppath.'/'.$path;
    		}
    	}
		
    	
    	//merge app config paths with core app paths  	
    	if(isset($conf->all['path'])) {
    	    $conf->all['path'] = $conf->arrayMergeRecursive(self::getDefaultAppPaths($apppath), $conf->all['path']);
    	}
    	else {
			//fix a notice in case [all] section doesn't exists, we create it
			if(!isset($conf->all)) $conf->all = array();
    		$conf->all['path'] = self::getDefaultAppPaths($apppath);
    	}

    	//try to merge array section 'all' with current environment section if exists
    	if(isset($conf->all) && isset($conf->$env)) {
    		$conf->setVars($conf->arrayMergeRecursive($conf->all,$conf->$env));
    	}
    	elseif(isset($conf->$env)) $conf->setVars($conf->$env);
    	else $conf->setVars($conf->all);

    	//save transformed config to registry
    	Peak_Registry::set('config', $conf);  	   	

    	//set some php ini settings
    	if(isset($conf->php)) {
    		foreach($conf->php as $setting => $val) {
    			if(!is_array($val)) ini_set($setting, $val);
    			else {
    				foreach($val as $k => $v) ini_set($setting.'.'.$k, $v);
    			}    			
    		}
    	}
    }

    /**
     * Generate an array of paths that represent all application subfolders
     *
     * @param string $app_path Current application absolute path
     */
    public static function getDefaultAppPaths($app_path)
    {  	
    	return array('application'         => $app_path,
    	             'cache'               => $app_path.'/cache',
    	             'controllers'         => $app_path.'/controllers',
    	             'controllers_helpers' => $app_path.'/controllers/helpers',
    	             'models'              => $app_path.'/models',
    	             'modules'             => $app_path.'/modules',
    	             'lang'                => $app_path.'/lang',
    	             'views'               => $app_path.'/views',
    	             'views_ini'           => $app_path.'/views/ini',
    	             'views_helpers'       => $app_path.'/views/helpers',
    	             'views_themes'        => $app_path.'/views',
    	             'theme'               => $app_path.'/views',
    	             'theme_scripts'       => $app_path.'/views/scripts',
    	             'theme_partials'      => $app_path.'/views/partials',
                     'theme_layouts'       => $app_path.'/views/layouts',
                     'theme_cache'         => $app_path.'/views/cache');
    }

    /**
     * Get environment in .htaccess or from constant APPLICATION_DEV and store it to $_env
     * If environment if already stored in $_env, we return it instead.
     * Define APPLICATION_DEV if not already defined.
     * 
     * @return string
     */
    public static function getEnv()
    {
    	if(!isset(self::$_env)) {
    		if(!defined('APPLICATION_ENV'))	$env = getenv('APPLICATION_ENV');
    		else $env = APPLICATION_ENV;
    		if(!in_array($env,array('development', 'testing', 'staging', 'production'))) {
    			self::$_env = 'production';
    		}
    		else self::$_env = $env;   		
    		if(!defined('APPLICATION_ENV'))	define('APPLICATION_ENV', self::$_env);		
    	}
    	return self::$_env;	
    }

    /**
     * Get application path vars from Peak_Registry::o()->core_config
     *
     * @param   string $path
     * @return  string|null
     * 
     * @example Peak_Core::getPath('application') = Peak_Registry::o()->config->path['application']
     */
    public static function getPath($path = 'application') 
    {
    	$c = Peak_Registry::o()->config;
    	
    	if(isset($c->path[$path])) return $c->path[$path];
    	else return null;
    }

    /**
     * Framework booting level
     *
     * @param  integer $level
     * @return null|Peak_Application
     */
    public static function init($level = 1)
    {
        //LEVEL 1 - only peak basic config / include path
        if($level >= 1) {
            
            //define server document root absolute path
            $svr_path = str_replace('\\','/',realpath($_SERVER['DOCUMENT_ROOT']));
            if(substr($svr_path, -1, 1) !== '/') $svr_path .= '/';
            define('SVR_ABSPATH', $svr_path); unset($svr_path);
            
            //define libray path
            define('LIBRARY_ABSPATH', str_ireplace(array(substr(__FILE__, -14),'\\'), array('','/'), __FILE__));
            
            //add LIBRARY_ABSPATH to include path
            set_include_path(implode(PATH_SEPARATOR, array(LIBRARY_ABSPATH,
														   LIBRARY_ABSPATH.'/Peak/Vendors',
														   get_include_path())));
        }
  
        //LEVEL 2 - load peak core autoloader
        if($level >= 2) include LIBRARY_ABSPATH.'/Peak/autoload.php';
        
        //LEVEL 3 - peak basic config with app config
		//need constant PUBLIC_ROOT and APPICATION_ROOT to work properly
        if($level >= 3) {

            if(!defined('PUBLIC_ROOT'))
				throw new Peak_Exception('ERR_CORE_INIT_CONST_MISSING', array('Public root','PUBLIC_ROOT'));
			if(!defined('APPLICATION_ROOT'))
			    throw new Peak_Exception('ERR_CORE_INIT_CONST_MISSING', array('Application root','APPLICATION_ROOT'));
				
            define('PUBLIC_ABSPATH', SVR_ABSPATH . PUBLIC_ROOT);
            define('APPLICATION_ABSPATH', realpath(SVR_ABSPATH . APPLICATION_ROOT));
			
			//if ZEND_LIB_ABSPATH is specified, we add it to include path
            if(defined('ZEND_LIB_ROOT')) {
				define('ZEND_LIB_ABSPATH',SVR_ABSPATH.ZEND_LIB_ROOT);
				set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), ZEND_LIB_ABSPATH)));
			}
        }
      
        //LEVEL 4 - peak app config init
        if($level >= 4) {
            
            //init app&core configurations
            if(!defined('APPLICATION_CONFIG')) {
				if(self::getEnv() !== 'development') {
					throw new Peak_Exception('ERR_CORE_INIT_CONST_MISSING', array('Configuration filename','APPLICATION_CONFIG'));
				}
				else {
					define('APPLICATION_CONFIG', 'genericapp.ini');
					self::initConfig('a.genericapp', APPLICATION_ABSPATH);
				}
            }
			else self::initConfig(APPLICATION_CONFIG, APPLICATION_ABSPATH);
        }
        
        //LEVEL 5 - peak app object init
        if($level >= 5) {
            
            //include application bootstrap if exists
            if(file_exists(APPLICATION_ABSPATH.'/bootstrap.php')) include APPLICATION_ABSPATH.'/bootstrap.php';

            //include application front extension if exists
            if(file_exists(APPLICATION_ABSPATH.'/front.php')) include APPLICATION_ABSPATH.'/front.php';
            
            return new Peak_Application();
        }
    }
}