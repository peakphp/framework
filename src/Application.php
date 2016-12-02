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
     * application config
     * @var object
     */
    protected $_config = null;

	/**
	 * Start framework
     */
    private function __construct(Config $conf)
    {   
        // application config             
        $this->_config = $conf;

        // register application/view/router instance
        Registry::set('app', $this);
        Registry::set('view', new View());
        Registry::set('router', $router = new Router($this->config('path.public')));
		
		$this->_loadBootstrap();
		$this->_loadFront();
    }

    /**
     * Create a instance of application
     * 
     * @param  array $config
     * @return object
     */
    static function create(Array $config) 
    {
        return new self(new Config($config));
    }

    /**
     * Access to application config object
     * 
     * @param  string|null $path 
     * @param  mixed|null  $value
     * @return mixed
     */
    public function config($path = null, $value = null)
    {
        if(!isset($path)) {
            return $this->_config;
        }
        elseif(!isset($value)) {
            return $this->_config->get($path);
        }
        else {
            $this->_config->set($path, $value);
            return $this;
        }
    }

	/**
	 * Load and store application Bootstrapper
	 *
	 * @param string $prefix Bootstrap class prefix name if exists
	 */
	private function _loadBootstrap()
	{
		$cname = $this->config('ns').'\Bootstrap';
		if(class_exists($cname)) $this->bootstrap = new $cname();
		else $this->bootstrap = new Bootstrap();
	}

	/**
	 * Load and store application Front Controller
	 *
	 * @param string $prefix Front class prefix name if exists
	 */
	private function _loadFront()
	{
		$cname = $this->config('ns').'\Front';
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