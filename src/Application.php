<?php
/**
 * Load the framework objects, application bootstrap and front controller.
 *   
 * @author    Francois Lajoie
 * @version   $Id$
 * @exception Peak_Exception
 */
class Peak_Application
{
	/**
	 * app bootstrap object if exists
	 * @var object
	 */
    public $bootstrap;

    /**
     * app object front controller
     * @var object
     */
    public $front;
    
    /**
     * Module app controller
     * @var object|null
     */
    public $module = null;

	/**
	 * Start framework
	 */
    public function __construct()
    {                
        // register application/view/router instance
        Peak_Registry::set('app', $this);
        Peak_Registry::set('view', new Peak_View());
        Peak_Registry::set('router', $router = new Peak_Router(PUBLIC_ROOT));
		
        // load app bootstrap
		$this->loadBootstrap();
        
        // load front controller
		$this->loadFront();
    }

	/**
	 * Load and store application Bootstrapper
	 *
	 * @param string $prefix Bootstrap class prefix name if exists
	 */
	public function loadBootstrap($prefix = '')
	{
		$cname = $prefix.'Bootstrap';
		if(class_exists($cname,false)) $this->bootstrap = new $cname();
		else $this->bootstrap = null;
	}

	/**
	 * Load and store application Front Controller
	 *
	 * @param string $prefix Front class prefix name if exists
	 */
	public function loadFront($prefix = '')
	{
		$cname = $prefix.'Front';
		$this->front = (class_exists($cname,false)) ? new $cname() : new Peak_Controller_Front();
	}

    /**
     * Start front dispatching
     * @see Peak_Controller_Front::dispatch() for param
     */
    public function run()
    {
    	$this->front->getRoute();
    	$this->front->preDispatch();
    	$this->front->dispatch();
        $this->front->postDispatch();
    	
    	return $this;
    }

    /**
     * Call front controller render() method
     */
    public function render()
    {
    	$this->front->controller->render();
    	$this->front->postRender();
    }
}