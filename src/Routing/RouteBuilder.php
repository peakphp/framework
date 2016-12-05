<?php
namespace Peak\Routing;

use Peak\Routing\Request;
use Peak\Routing\RequestResolver;
use Peak\Routing\Route;

class RouteBuilder
{


    /**
     * Resolve a request
     * 
     * @return Route
     */
    public static function get(...$segment) 
    {
        $route_arr = [];

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
        return $resolve->getRoute();
    }
}