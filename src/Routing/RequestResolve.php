<?php
namespace Peak\Routing;

use Peak\Routing\Request;
use Peak\Routing\Route;

class RequestResolve
{

    /**
     * Request object
     * @var Request
     */
    protected $request;

    /**
     * Contructor
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
    public function getRoute()
    {
        $request_block = explode(Request::$separator, $this->request->request_uri);

        foreach($request_block as $key => $value) {
            if (strlen($value) == 0) unset($request_block[$key]);
        }

        $route = new Route();

        $route->base_uri     = $this->request->base_uri;
        $route->request_uri  = $this->request->request_uri;
        $route->raw_uri      = $this->request->raw_uri;
        
        $route->controller   = array_shift($request_block);
        $route->action       = array_shift($request_block);
        $route->action       = (empty($route->action)) ? '' : $route->action;
        $route->params       = $request_block;
        $route->params_assoc = $this->paramsToAssoc($route->params);

        return $route;
    }

    /**
     * Transform params array to params associate array
     * To work, we need a pair number of params to transform it to key/val array
     */
    protected function paramsToAssoc($params)
    {
        $i = 0;
        $params_assoc = [];

        foreach($params as $k => $v) {
            if($i == 0) {
                $key = $v; 
                ++$i;
            }
            else {
                $params_assoc[$key] = $v;
                $i = 0;
            }
        }

        return $params_assoc;
    }
}