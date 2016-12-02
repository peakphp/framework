<?php
namespace Peak;

use Peak\Application\Bootstrap;
use Peak\Application\Config;

/**
 * Load the framework objects, application bootstrap and front controller.
 */
class Application
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


    public $config = null;

	/**
	 * Start framework
     */
    private function __construct(Config $conf)
    {   
        // application config             
        $this->config = $conf;

        //print_r($this->config);


        // register application/view/router instance
        Registry::set('app', $this);
        Registry::set('view', new View());
        Registry::set('router', $router = new Router($this->config->path['public']));

		
        // load app bootstrap
		$this->loadBootstrap();
        
        // load front controller
		$this->loadFront();
    }

    static function create($config) 
    {
        return new self(new Config($config));
    }
	/**
	 * Load and store application Bootstrapper
	 *
	 * @param string $prefix Bootstrap class prefix name if exists
	 */
	public function loadBootstrap($prefix = 'App\\')
	{
		$cname = $prefix.'Bootstrap';
		if(class_exists($cname)) $this->bootstrap = new $cname();
		else $this->bootstrap = new Bootstrap();

        //print_r($this->bootstrap); die('YE');
	}

	/**
	 * Load and store application Front Controller
	 *
	 * @param string $prefix Front class prefix name if exists
	 */
	public function loadFront($prefix = 'App\\')
	{
		$cname = $prefix.'Front';
		$this->front = (class_exists($cname)) ? new $cname() : new Controller\Front();
	}

    /**
     * Start front dispatching
     * @see Peak\Controller\Front::dispatch() for param
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