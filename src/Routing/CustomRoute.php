<?php

declare(strict_types=1);

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
    /**
     * CustomRoute constructor.
     *
     * @param string $regex
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $regex, string $controller, string $action = '')
    {
        $this->regex = Regex::build($regex);
        $this->controller = trim($controller);
        $this->action     = trim($action);
    }

    /**
     * Get the current regex
     *
     * @return string
     */
    public function getRegex(): string
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
        preg_match(
            '#^/'.$this->regex.'/$#',
            $request->request_uri,
            $matches
        );

        //we got a positive preg_match
        if (!empty($matches)) {
            $request->request_uri = $this->controller . Request::$separator . $this->action . $request->request_uri;
            $request_resolve = new RequestResolver($request);
            $route = $request_resolve->getRoute();
            return $route;
        }

        return false;
    }
}
