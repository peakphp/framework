<?php

declare(strict_types=1);

namespace Peak\Bedrock\Controller;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Exceptions\NoRouteFoundException;
use Peak\Bedrock\Application\Exceptions\ControllerNotFoundException;
use Peak\Routing\Route;
use Peak\Routing\RouteBuilder;

/**
 * Class FrontController
 * @package Peak\Bedrock\Controller
 */
class FrontController
{
    /**
     * Route object
     * @var \Peak\Routing\Route
     */
    public $route;
    
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
     * FrontController constructor.
     *
     * @throws Application\Exceptions\InstanceNotFoundException
     * @throws Application\Exceptions\MissingContainerException
     */
    public function __construct()
    {
        $config = Application::conf('front');
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    /**
     * Called before routing dispatching
     * Empty by default
     */
    public function preDispatch()
    {
    }

    /**
     * Called after controller action dispatching
     * Empty by default
     */
    public function postDispatch()
    {
    }

    /**
     * Called after controller loading
     * Empty by default
     */
    public function postDispatchController()
    {
    }

    /**
     * Called after rendering controller view
     * Empty by default
     */
    public function preRender()
    {
    }

    /**
     * Called after rendering controller view
     * Empty by default
     */
    public function postRender()
    {
    }

    /**
     * Call appropriate dispatching methods
     *
     * @throws ControllerNotFoundException
     * @throws NoRouteFoundException
     */
    public function dispatch()
    {
        $this->dispatchController();
        // execute a normal controller action
        if ($this->controller instanceof ActionController) {
            $this->dispatchControllerAction();
        }
    }

    /**
     * Set a new request and redispatch the controller
     *
     * @param $ctrl
     * @param string $action
     * @param null $params
     * @throws ControllerNotFoundException
     * @throws NoRouteFoundException
     */
    public function redirect($ctrl, $action = 'index', $params = null)
    {
        $this->route = RouteBuilder::get($ctrl, $action, $params);

        if ((is_object($this->controller)) && (strtolower($ctrl) === strtolower($this->controller->getTitle()))) {
            $this->controller->setRoute($this->route);
            $this->controller->dispatchAction();
        } else {
            $this->dispatch();
        }
    }

    /**
     * Dispatch appropriate controller according to the router
     *
     * @throws Application\Exceptions\InstanceNotFoundException
     * @throws Application\Exceptions\MissingContainerException
     * @throws ControllerNotFoundException
     * @throws NoRouteFoundException
     */
    protected function dispatchController()
    {
        if ($this->route === null) {
            $request = Application::get('AppRouting')->request->request_uri;
            throw new NoRouteFoundException($request);
        }

        //set default controller if router doesn't have one
        if (!isset($this->route->controller)) {
            $this->route->controller = $this->default_controller;
        }

        //set controller class name
        $ctrl_name = $this->_getCtrlName(Application::conf('ns').'\Controllers\\', $this->route->controller);

        //check if it's valid application controller
        if (!class_exists($ctrl_name)) {
            throw new ControllerNotFoundException($this->route->controller);
        }

        $this->controller = Application::create($ctrl_name);

        if ($this->controller instanceof ActionController) {
            $this->controller->setRoute($this->route);
            $this->postDispatchController();
        }
    }
    
    /**
     * Dispatch action of controller
     *
     * @throws \Exception
     */
    protected function dispatchControllerAction()
    {
        if ($this->controller instanceof ActionController) {
            $this->controller->dispatch();
        }
    }

    /**
     * Get controller name
     *
     * @param  string $ns   namespace prefix
     * @param  string $name controller prefix name
     * @return string
     */
    protected function _getCtrlName($ns, $name)
    {
        return $ns.ucfirst($name).'Controller';
    }

    /**
     * Force dispatch of $error_controller
     *
     * @param null $exception
     * @throws Application\Exceptions\InstanceNotFoundException
     * @throws Application\Exceptions\MissingContainerException
     * @throws ControllerNotFoundException
     * @throws NoRouteFoundException
     */
    public function errorDispatch($exception = null)
    {
        if (!$this->route instanceof Route) {
            $this->route = new Route();
        }

        $this->route->controller = $this->error_controller;
        $this->route->action     = 'index';

        $this->dispatchController();

        if (isset($exception)) {
            $this->controller->exception = $exception;
        }
        
        $this->dispatchControllerAction();
    }
}
