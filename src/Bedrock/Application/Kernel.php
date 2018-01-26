<?php

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application;
use Peak\Bedrock\Controller\FrontController;

/**
 * Load the framework objects, application bootstrap and front controller.
 */
class Kernel
{
    /**
     * Framework version
     */
    const VERSION = '2.24.0';
    
    /**
     * App bootstrap object if exists
     * @var Bootstrapper
     */
    public $bootstrap;

    /**
     * App object front controller
     * @var FrontController
     */
    public $front;

    /**
     * App routing object
     * @var Routing
     */
    public $routing;
    
    /**
     * App config
     * @var Config
     */
    protected $config;

    /**
     * Start framework
     */
    public function __construct(Config $config, Routing $routing)
    {
        // application config
        $this->config = $config;

        // application routing
        $this->routing = $routing;

        $this->loadBootstrap();
        $this->loadFront();
    }

    /**
     * Reload application Bootstrapper and FrontController for a module
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
     */
    private function loadBootstrap()
    {
        $cname = $this->config->get('ns').'\Bootstrap';
        if (class_exists($cname)) {
            $this->bootstrap = new $cname(Application::container());
        }
    }

    /**
     * Load and store application FrontController
     */
    private function loadFront()
    {
        $cname = $this->config->get('ns').'\Front';
        if (!class_exists($cname)) {
            $cname = FrontController::class;
        }
        $this->front = new $cname();
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
