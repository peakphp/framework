<?php

namespace Peak\Routing;

class Route
{
    /**
     * Unprocess request uri
     * @var string
     */
    public $raw_uri;

    /**
     * default url relative root set with __construct()
     * @var string
     */
    public $base_uri;

    /**
     * request uri without base_uri.
     * @var string
     */
    public $request_uri;

    /**
     * @deprecated
     * Original unparsed request array
     * @var array
     */
    public $request = [];

    /**
     * Controller name
     * @var string
     */
    public $controller;

    /**
     * Requested action
     * @var string
     */
    public $action;

    /**
     * action param(s) array
     * @var array
     */
    public $params = [];

    /**
     * Actions param(s) associative array
     * @var array
     */
    public $params_assoc = [];

    /**
     * Check if match the current route
     *
     * @param  string  $controller the controller name 
     * @param  string  $action     if specified, look also for action name
     * @return boolean
     */
    public function is($controller, $action = null) 
    {
        if (isset($action)) {
            return ($this->controller === $controller && $this->action === $action);
        }
        return ($this->controller === $controller);
    }
}
