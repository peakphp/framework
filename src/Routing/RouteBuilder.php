<?php
namespace Peak\Routing;

use Peak\Routing\Request;
use Peak\Routing\RequestResolver;
use Peak\Routing\Route;

class RouteBuilder
{

    protected $route;

    /**
     * Contructor
     *
     * @param  Request $request
     */
    public function __construct(...$segment)
    {
        $this->get(...$segment);
    }



    /**
     * Resolve a request
     * 
     * @return Route
     */
    public function get(...$segment) 
    {
        $this->route = new Route();

        $route_arr = [];

        //print_r($segment);

        foreach($segment as $e) {
            if(is_string($e) || is_numeric($e)) {
                $route_arr[] = $e;
            }
            elseif(is_array($route_arr)) {
                foreach($e as $ek => $ev) {
                    $route_arr[] = $ek;
                    $route_arr[] = $ev;
                } 
            }
        }

        $resolve = new RequestResolver(new Request($route_arr));
        $this->route = $resolve->getRoute();

        return $this->route;
    }
}