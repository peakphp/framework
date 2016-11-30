<?php
namespace Peak\Controller;

use Peak\Core;
use Peak\Registry;
use Peak\Exception;

/**
 * Front controller
 */
class Front
{
	/**
	 * Router object
	 * @var object
	 */
	public $router;
	
	/**
	 * Controller object
	 * @var object
	 */
	public $controller;

	/**
	 * Default controller name
	 * @var string
	 */
	public $default_controller = 'index';
	
	/**
	 * Exception|error controller (used by errorDispatch())
	 * @var string
	 */
	public $error_controller = 'error';
	
	/**
	 * Allow/Disallow the use of Peak library internal controllers
	 * @var bool
	 */
	public $allow_internal_controllers = false;
	
	/**
	 * Allow/Disallow application modules
	 * @var bool
	 */
	public $allow_app_modules = true;
	
	/**
	 * Allow/Disallow Peak library internal modules
	 * @var bool
	 */
	public $allow_internal_modules = true;
	
	
	/**
	 * class construct
	 */
	public function __construct()
	{
		$this->router = Registry::o()->router;
		$this->_registryConfig();
	}
	
	/**
     * Get array 'front' from registered object 'config' if exists
     */
    private function _registryConfig()
    {
    	if(isset(Registry::o()->config->front)) {
    		foreach(Registry::o()->config->front as $k => $v) {
    			if($k === 'allow_internal_controllers') $v = (bool)$v;
    			$this->$k = $v;
    		}
    	}
    }

	/**
	 * Initialize router uri request
	 */
	public function getRoute()
	{
		$this->router->getRequestURI();
	}

	/**
	 * Called before routing dispatching
	 * Empty by default
	 */
	public function preDispatch() {	}

	/**
	 * Call appropriate dispatching methods
	 */
	public function dispatch()
	{
	    $this->_dispatchController();
	    
	    if($this->controller instanceof Peak\Application\Modules) {
        	$this->_dispatchModule();
        }               
        // execute a normal controller action
        elseif($this->controller instanceof Action) {
            $this->_dispatchControllerAction(); 
        }
	}
	
	/**
	 * Dispatch appropriate controller according to the router
	 */
	protected function _dispatchController()
	{
        //set default controller if router doesn't have one
        if(!isset($this->router->controller)) {
            $this->router->controller = $this->default_controller;
        }
        
        //set controller class name
        $ctrl_name = 'App\Controllers\\'.$this->router->controller;

        // echo $this->router->controller;
        // echo '<pre>';
        // print_r($this->router);

        //check if it's valid application controller
        if(!class_exists($ctrl_name))
        {
            $internal_ctrl_name = 'Peak\Controller\Internal\\'.$this->router->controller;

            //check for peak internal controller
            if(($this->allow_internal_controllers === true) && (class_exists($internal_ctrl_name))) {
                $this->controller = new $internal_ctrl_name();
            }
            else throw new Exception('ERR_CTRL_NOT_FOUND', $this->router->controller);
        }
        else $this->controller = new $ctrl_name();

		$this->postDispatchController();
	}
	
	/**
	 * Dispatch action of controller
	 */
	protected function _dispatchControllerAction()
	{
	    $this->controller->dispatch(); 
	}
	
	/**
	 * Dispatch a module and run it
	 */
	protected function _dispatchModule()
	{
	    Registry::o()->app->module = $this->controller;
        $this->controller->run();
	}
	
	/**
	 * Force dispatching to a specific controller/action
	 * @deprecated
	 *
	 * @param string $ctrl
	 * @param string $action
	 */
	public function forceDispatch($controller, $action = 'index')
	{
		$this->router->controller = $controller;
		$this->router->action = $action;
		$this->dispatch();
	}
	
	/**
	 * Force dispatch of $error_controller
     *
     * @param object $exception
	 */
	public function errorDispatch($exception = null)
	{
		$this->router->controller = $this->error_controller;
		$this->router->action     = 'index';
		
		$this->_dispatchController();

        if(isset($exception)) {
            $this->controller->exception = $exception;
        }
        
        $this->_dispatchControllerAction();
        
        return Registry::o()->app;
	}

    /**
     * Set a new request and redispatch the controller
     *
     * @param string     $ctrl
     * @param string     $action
     * @param array/null $params
     */
    public function redirect($ctrl, $action = 'index', $params = null)
    {
	    $request = array($ctrl, $action);
		
		if(isset($params)) {
		    if(is_array($params)) $request = array_merge($request, $params);
		    else $request[] = $params;
		}
		
    	$this->router->setRequest($request);
    	
    	//if redirection is in the same controller, we don't want to reload controller
        //and call twice preAction and postAction methods
    	if((is_object($this->controller)) && (strtolower($ctrl) === strtolower($this->controller->getTitle()))) {

            $this->controller->getRoute();
            $this->controller->dispatchAction();
        }
    	else $this->dispatch();
    }

    /**
	 * Called after controller action dispatching
	 * Empty by default
	 */
    public function postDispatch() { }
	
	/**
	 * Called after controller loading
	 * Empty by default
	 */
	public function postDispatchController() { }
    
    /**
     * Called after rendering controller view
     * Empty by default
     */
    public function postRender() { }   

    /**
     * Check if modules dirname exists
     *
     * @param  string $name
     * @return bool
     */
    public function isModule($name)
    {
    	return (file_exists(Core::getPath('modules').'/'.$name)) ? true : false;
    }
}