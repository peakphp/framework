<?php

namespace Peak\Bedrock\Controller;

use \Exception;
use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Module;
use Peak\Bedrock\Controller\Action;
use Peak\Routing\RouteBuilder;

/**
 * Front controller
 */
class Front
{
    /**
     * Route object
     * @var Peak\Routing\Route
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
     * class construct
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
     */
    public function dispatch()
    {
        $this->_dispatchController();
        // execute a normal controller action
        if ($this->controller instanceof Action) {
            $this->_dispatchControllerAction();
        }
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
     */
    protected function _dispatchController()
    {
        //set default controller if router doesn't have one
        if (!isset($this->route->controller)) {
            $this->route->controller = $this->default_controller;
        }

        //set controller class name
        $ctrl_name = $this->_getCtrlName(Application::conf('ns').'\Controllers\\', $this->route->controller);

        //check if it's valid application controller
        if (!class_exists($ctrl_name)) {
            throw new Exception('Application controller '.$this->route->controller.' not found');
        }

        $this->controller = Application::instantiate($ctrl_name);

        if ($this->controller instanceof Action) {
            $this->controller->setRoute($this->route);
            $this->postDispatchController();
        }
    }
    
    /**
     * Dispatch action of controller
     */
    protected function _dispatchControllerAction()
    {
        if ($this->controller instanceof Action) {
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
     * @param object $exception
     */
    public function errorDispatch($exception = null)
    {
        $this->route->controller = $this->error_controller;
        $this->route->action     = 'index';

        $this->_dispatchController();

        if (isset($exception)) {
            $this->controller->exception = $exception;
        }
        
        $this->_dispatchControllerAction();
    }
}
