<?php
namespace Peak\Routing;

use Peak\Routing\Request;
use Peak\Routing\Route;

class Regex
{

    /**
     * Request object
     * @var Request
     */
    public $regex;

    public $controller;

    public $action;

    private $_quick_reg = [
        ':any'       => '[^\/]+',

        ':negnum'    => '-[0-9]+',
        ':posnum'    => '[0-9]+',
        ':num'       => '-?[0-9]+',

        // for float pattern, string like .5 is not valid, it must be 0.5
        ':negfloat'  => '-([0-9]+\.[0-9]+|[0-9]+)',     
        ':posfloat'  => '([0-9]+\.[0-9]+|[0-9]+)',     
        ':float'     => '[-+]?([0-9]+\.[0-9]+|[0-9]+)',

        // chars, numbers, -, _ and + only
        ':permalink' => '[a-zA-Z0-9+_-]+',

        ':alphanum'  => '[a-zA-Z0-9]+',
        ':alpha'     => '[a-zA-Z]+',

        ':year'      => '[12][0-9]{3}',            // 1000 to 2999
        ':month'     => '0[1-9]|1[012]|[1-9]',     // valid ex: 07, 7, 12, 31
        ':day'       => '[12][0-9]|3[01]|0?[1-9]', // valid ex: 2, 12, 02, 15, 31
    ];

    /**
     * Contructor
     *
     * @param  Request $request
     */
    public function __construct($regex, $controller, $action = '')
    {
        $this->setRegex($regex);

        $this->controller = $controller;
        $this->action     = $action;
    }


    /**
     * Set a regex
     * 
     * @param string $regex
     */
    public function setRegex($regex)
    {
        // look for {param_name}:validator
        // so if i got an url like http://mysite.com/editor/{id}:num
        // valid url would be ex:  http://mysite.com/editor/id/128
        $regex = preg_replace('#\{([a-zA-Z0-9_-]+)\}:([a-z]+)#', '$1/(?<$1>:$2)', $regex);

        // replace quick pattern to a standard regex expression
        $this->regex = str_ireplace(
            array_keys($this->_quick_reg), 
            array_values($this->_quick_reg), 
            $regex
        );
    }

    /**
     * Check if match
     * 
     * @param  Request $req  
     * @return mixed        Return a route if valid, otherwise false
     */
    public function match(Request $request)
    {

        $result = preg_match(
            '#^/'.$this->regex.'/$#', 
            $request->request_uri, 
            $matches
        );

        //we got a positive preg_match
        if(!empty($matches)) {


            // $arr_assoc = (array_keys($matches) !== range(0, count($matches) - 1));
                        
            // if($arr_assoc === true) {
            //     $rex_params = array_slice($matches,1);
            //     $params = array();
            //     foreach($rex_params as $i => $p) {
            //         $params[] = (is_string($i)) ? $i : $p;
            //     }
            //     //print_r($params);
            // }

            $request->request_uri = $this->controller.Request::$separator.$this->action.$request->request_uri;

            $request_resolve = new RequestResolve($request);
            $route = $request_resolve->getRoute();

            return $route;
        }

        else return false;
    }   
}