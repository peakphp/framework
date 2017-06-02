<?php

namespace Peak\Routing;

use Peak\Routing\Regex;
use Peak\Routing\RequestResolver;

class CustomRoute
{
    /**
     * Controller name
     * @var string
     */
    public $controller;

    /**
     * Controller action name
     * @var string
     */
    public $action;

    /**
     * Regex string
     * @var string
     */
    protected $regex;

    /**
     * Constructor
     *
     * @param  Request $request
     */
    public function __construct($regex, $controller, $action = '')
    {
        $this->setRegex($regex);

        $this->controller = trim($controller);
        $this->action     = trim($action);
    }

    /**
     * Set a regex
     *
     * @param string $regex
     */
    public function setRegex($regex)
    {
        $this->regex = Regex::build($regex);
    }

    /**
     * Get the current regex
     *
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Check if match
     *
     * @param  Request $req
     * @return mixed   Return a route if valid, otherwise false
     */
    public function matchRequest(Request $request)
    {
        $result = preg_match(
            '#^/'.$this->regex.'/$#',
            $request->request_uri,
            $matches
        );

        //we got a positive preg_match
        if (!empty($matches)) {
            $request->request_uri = $this->controller.Request::$separator.$this->action.$request->request_uri;
            $request_resolve = new RequestResolver($request);
            $route = $request_resolve->getRoute();

            return $route;
        }

        return false;
    }
}
