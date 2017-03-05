<?php

namespace Peak;

use Peak\Collection;
use Peak\Application\Bootstrapper;
use Peak\Application\Config;
use Peak\Application\Routing;
use Peak\Controller\Front;

/**
 * Load the framework objects, application bootstrap and front controller.
 */
class Application
{

    /**
     * app bootstrap object if exists
     * @var Application\Bootstrap
     */
    public $bootstrap;

    /**
     * app object front controller
     * @var Controller\Front
     */
    public $front;

    /**
     * Application routing object
     * @var Application\Routing
     */
    public $routing;
    
    /**
     * application config
     * @var Application\Config
     */
    protected $config = null;

    /**
     * Start framework
     */
    private function __construct(Collection $conf)
    {
        // application config
        $this->config = $conf;

        // register application/view instance
        Registry::set('app', $this);
        Registry::set('view', new View());

        $this->routing = new Routing();

        $this->loadBootstrap();
        $this->loadFront();
    }

    /**
     * Create a instance of application
     *
     * @param  array $config
     * @return Application
     */
    public static function create(array $config)
    {
        $config = new Config($config);
        return new static($config->getMountedConfig());
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
        if (!isset($path)) {
            return $this->config;
        } elseif (!isset($value)) {
            return $this->config->get($path);
        }
        
        $this->config->set($path, $value);
        return $this;
    }

    /**
     * Static version of config() use current Application instance in Registry
     *
     * @return $this
     */
    public static function conf($path = null, $value = null)
    {
        if (!isset($path)) {
            return Registry::o()->app->config();
        } elseif (!isset($value)) {
            return Registry::o()->app->config($path);
        }

        Registry::o()->app->config($path, $value);
        return $this;
    }

    /**
     * Reload application bootstrapper and front for a module
     *
     * @return $this
     */
    public function reload()
    {
        $this->loadBootstrap();
        $this->loadFront();
        return $this;
    }

    /**
     * Load and store application Bootstrapper
     *
     * @param string $prefix Bootstrap class prefix name if exists
     */
    private function loadBootstrap()
    {
        $cname = $this->config('ns').'\Bootstrap';
        $this->bootstrap = (class_exists($cname)) ? new $cname() : new Bootstrapper();
    }

    /**
     * Load and store application Front Controller
     */
    private function loadFront()
    {
        $cname = $this->config('ns').'\Front';
        $this->front = (class_exists($cname)) ? new $cname() : new Front();
    }

    /**
     * Start front dispatching of a request
     *
     * @param  mixed $request if specified, force the request,
     *         otherwise, it will use server request uri
     * @return $this
     */
    public function run($request = null)
    {
        $this->routing->loadRequest($request);
        $this->front->route = $this->routing->getRoute();

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
        $this->front->preRender();
        $this->front->controller->render();
        $this->front->postRender();
    }
}
