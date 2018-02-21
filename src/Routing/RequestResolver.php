<?php

namespace Peak\Routing;

use Peak\Routing\Request;
use Peak\Routing\Route;
use Peak\Common\Collection;

class RequestResolver
{
    /**
     * Request object
     * @var Request
     */
    protected $request;

    /**
     * Constructor
     *
     * @param  Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Resolve a request
     *
     * @return Route
     */
    public function getRoute(Collection $regex_routes = null, $auto_routing = true)
    {
        $route = $this->lookForRegexMatch($regex_routes);

        if ($route !== false) {
            return $route;
        } elseif (!$auto_routing && $this->request->request_uri !== '/') {
            return null;
        }

        // go with auto-routing
        return $this->resolve();
    }

    /**
     * Resolve normal request (auto-routing)
     *
     * @return Route
     */
    protected function resolve()
    {
        $request_block = explode(Request::$separator, $this->request->request_uri);

        foreach ($request_block as $key => $value) {
            if (strlen($value) == 0) {
                unset($request_block[$key]);
            }
        }

        $route = new Route();

        $route->base_uri     = $this->request->base_uri;
        $route->request_uri  = $this->request->request_uri;
        $route->raw_uri      = $this->request->raw_uri;
        
        $route->controller   = array_shift($request_block);
        $route->action       = array_shift($request_block);
        $route->action       = (empty($route->action)) ? '' : $route->action;
        $route->params       = $request_block;

        return $route;
    }

    /**
     * Try to match the current request with a custom regex route
     *
     * @param  Collection|null $regex_routes
     * @return mixed
     */
    protected function lookForRegexMatch(Collection $regex_routes = null)
    {
        if (isset($regex_routes)) {
            foreach ($regex_routes as $r) {
                $route = $r->matchRequest($this->request);
                if ($route instanceof Route) {
                    return $route;
                }
            }
        }
        return false;
    }
}
