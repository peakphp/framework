<?php
/**
 * Peak Modules Application Abstract Launcher 
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
abstract class Peak_Application_Modules
{

	/**
	 * Module name
	 * @var string
	 */
    protected $_name = '';
    
    /**
     * Module path
     * @var string
     */
    protected $_path = '';
    
    /**
     * Module type
     * @var bool
     */
    protected $_internal = false;
        
    /**
     * Init module, bootstrap and front controller if exists and run module
     */
    public function __construct()
    {      	
    	//prepare module
    	$this->prepare();
		
		//delete previously added router regex for the module
        Peak_Registry::o()->router->deleteRegex();
        
		$app_path = Peak_Core::getPath('application');		
		      
        //load initialize module bootstrap if exists
        if(file_exists($app_path.'/bootstrap.php')) include $app_path.'/bootstrap.php';
		Peak_Registry::o()->app->loadBootstrap($this->_name.'_');
		
		//initialize module front if exists, otherwise load peak default front
        if(file_exists($app_path.'/front.php')) include $app_path.'/front.php';
		Peak_Registry::o()->app->loadFront($this->_name.'_');
    }
    
    /**
     * Prepare modules app and init modules
     */
    protected function prepare()
    {
		$this->defineName();
		$this->definePath();

    	//overdrive application paths to modules folder
        $this->init($this->_name, $this->_path);
    }

	/**
	 * Define module name
	 */
	protected function defineName()
	{
		if(empty($this->_name)) {
			if(!($this->_internal))	$replace = 'controller';
			else $replace = 'Peak_Controller_Internal_';

			$this->_name = str_ireplace($replace,'',get_class($this));
		}
	}

	/**
	 * Define module path
	 */
	protected function definePath()
	{
		if(!($this->_internal))	{
    		$this->_path = Peak_Core::getPath('modules').'/'.$this->_name;
    	}
    	else {
    		$this->_path = LIBRARY_ABSPATH.'/Peak/Application/Modules/'.$this->_name;
    	}
	}

    /**
     * Overdrive core application paths configs to a module application paths.
     *
     * @param string $module  folder name of the module to load
     * @param string 
     */
    public function init($module, $path = null)
    {
        $config = Peak_Registry::o()->config;
    
        $module_path = (isset($path)) ? $path : $config->modules_path.'/'.$module;
        
        if(is_dir($module_path)) {
                        
            //backup previous application configs before overloading core configurations
            Peak_Registry::set('app_config', clone $config);
            
            $config->module_name = $module;
            
            //get default path structure for module path application
            $config->path = Peak_Core::getDefaultAppPaths($module_path);
        }  
    }

    /**
     * Run modules requested controller
     */
    public function run()
    {
        //add module name to the end Peak_Router $base_uri      
        Peak_Registry::o()->router->base_uri .= $this->_name.'/';
 
        //re-call Peak_Application run() for handling the new routing
        Peak_Registry::o()->app->run();
    }

    /**
     * Return the module name
     * 
     * @return string
     */
    public function getName()
    {
    	return $this->_name;
    }

    /**
     * Return module path
     *
     * @return string
     */
    public function getPath()
    {
    	return $this->_path;
    }

    /**
     * Return module location relative to the application
     * Will return true if the module is inside Peak library folder 
     * 
     * @return bool
     */
    public function isInternal()
    {
    	return $this->_internal;    	
    }
}