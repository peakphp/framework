<?php
/**
 * Router URL parser. Supporting $_GET url, standard rewrited url and regex url
 * For standard rewrited url and regex url, apache mod_rewrite is required          
 * 
 * @author   Francois Lajoie
 * @version  $Id$     
 */
class Peak_Router
{

    /**
     * default url relative root set with __construct()
     * @var string
     */
    public $base_uri;

    /**
     * $_SERVER['REQUEST_URI'] without base_uri.
     * @var string
     */
    public $request_uri;

    /**
     * Original unparsed request array
     * @var array
     */
    public $request;

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
    public $params = array();

    /**
     * Actions param(s) associative array
     * @var array
     */
    public $params_assoc = array();

    /**
     * Regex route
     * @var array
     */
    protected $_regex = array();


    /**
     * Set base url of your application
     * 
     * @example your application script page url is http://example.com/myapp/index.php  so $base_uri would be : '/myapp/'
     * @param   string $base_uri - Its recommended to use constant 'PUBLIC_ROOT' when instantiate this object
     */
    public function __construct($base_uri = '/')
    {
        $this->setBaseUri($base_uri);   
    }

    /**
     * Set the base of url request
     *
     * @param string $base_uri
     */
    public function setBaseUri($base_uri)
    {               
        //fix '/' missing at left and right of $base_uri
        if(substr($base_uri, 0, 1) !== '/') $base_uri = '/'.$base_uri;
        if(substr($base_uri, -1, 1) !== '/') $base_uri = $base_uri.'/';
        $this->base_uri = $base_uri;
    }

    /** 
     * Retreive request param(s) from url and save them to $request 
     * Work with/without rewrited url
     */
    public function getRequestURI()
    {  
        //ensure that the router vars are empty
        $this->reset();
        
        //fix $_SERVER['REQUEST_URI']
        $server_uri = $_SERVER['REQUEST_URI'];
        if(substr($server_uri, 0, 1) !== '/') $server_uri = '/'.$server_uri;
        if(substr($server_uri, -1, 1) !== '/') $server_uri = $server_uri.'/';
        
        //fix unwanted double slash
        $server_uri = str_ireplace('//', '/', $server_uri);
        
        //get server REQUEST_URI without base uri
        if($this->base_uri !== '/') $this->request_uri = str_ireplace($this->base_uri,'', $server_uri);
        else $this->request_uri = $server_uri;
        
        //fix request_uri to not begin/end with a slash
        if(substr($this->request_uri, 0, 1) === '/') $this->request_uri = substr($this->request_uri, 1);
        if(substr($this->request_uri, -1, 1) === '/') $this->request_uri = substr($this->request_uri, 0, -1);
        
        // if url is like index.php?key=val&key2... we use $_GET var instead
        if(preg_match('#\.php\??#',$this->request_uri)) {
            
            // fixed: app default controller was called on url rewrited
            // with fake path and url ending by .php extension witch is not good.
            $request_uri = explode('?',$this->request_uri);
            $request_uri = $request_uri[0];
            if(strpos($request_uri, '/') !== false) throw new Peak_Exception('ERR_ROUTER_URI_NOT_FOUND');
            else {
                foreach($_GET as $k => $v) {
                    $this->request[] = $k;
                    if(strlen($v) != 0) $this->request[] = $v;
                }
            }
        }
        //if its rewrited url
        else {
            //check for regex
            if($this->matchRegex()) return;
            
            $this->request = explode('/',$this->request_uri);
            foreach($this->request as $key => $value) {
                if (strlen($value) == 0) unset($this->request[$key]);
            }

        }
        $this->resolveRequest();
    }

    /**
     * Resolve $request
     */
    protected function resolveRequest()
    {
        // extract data from request
        if (!empty($this->request)) 
        {           
            //preserve unparsed request
            $request = $this->request;
            
            $this->controller = array_shift($request);
            $this->action = array_shift($request);
            $this->action = (empty($this->action)) ? '' : $this->action;
            $this->params = $request;
            $this->paramsToAssoc();
        }
    }

    /**
     * Reset router vars
     */
    public function reset()
    {
        $this->request = null;
        $this->controller = null;
        $this->action = null;
        $this->params = array();
        $this->params_assoc = array();
    }

    /**
     * Set manually a request and resolve it
     *
     * @param array $request
     */
    public function setRequest($request)
    {
        $this->reset();
        $this->request = $request;
        $this->resolveRequest();
    }

    /**
     * Transform params array to params associate array
     * To work, we need a pair number of params to transform it to key/val array
     */
    protected function paramsToAssoc()
    {
        $i = 0;
        foreach($this->params as $k => $v) {
            if($i == 0) { $key = $v; ++$i; }
            else { $this->params_assoc[$key] = $v; $i = 0; }
        }
    }

    /**
     * Add a regex route
     *
     * @param string $regex
     * @param array  $route
     * @param bool   $allow_quick_patterns if true, string like {param_name}:validator will 
     *                                     be accepted and transformed to their regex pattern
     */
    public function addRegex($regex, $route, $allow_quick_patterns = true)
    {
        if(is_array($route)) $this->_regex[$regex] = $route;
        else {

            //special route syntax
            if($allow_quick_patterns === true) {

                // look for {param_name}:validator
                // so if i got an url like http:://mysite.com/editor/{id}:num
                // valid url would be ex:  http:://mysite.com/editor/id/128
                $regex = preg_replace('#\{([a-zA-Z0-9_-]+)\}:([a-z]+)#', '$1/(?<$1>:$2)', $regex);

                // quick regex pattern
                $quick_reg = array(
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
                );

                // replace quick pattern to a standard regex expression
                $regex = str_ireplace(array_keys($quick_reg), array_values($quick_reg), $regex);
            }

            // parse route
            $route = explode('/', $route);

            // "register" regex
            $this->_regex[$regex] = array('controller' => $route[0], 'action' => $route[1]);
        }
        return $this;
    }

    /**
     * Try to match request uri to a regex
     *
     * @return bool
     */
    public function matchRegex()
    {
        //we got regex
        if(!empty($this->_regex)) {
            
            //check all regex for a match. First in, first out here
            foreach($this->_regex as $regex => $route) {

                $result = preg_match('#'.$regex.'#', $this->request_uri, $matches);

                //we got a positive preg_match
                if($result) {

                    //if url match a regexp but end up with additionnal data, the url should be not valid otherwise 
                    //we will have url that can ends with anything and still be valid for the application and google, wich its bad
                    if($this->request_uri === $matches[0]) {
                    
                        //set up controller and action
                        $this->controller = $route['controller'];
                        $this->action = $route['action'];
                            
                        //determine type of array (array assoc or not)
                        $arr_assoc = (array_keys($matches) !== range(0, count($matches) - 1));
                        
                        if($arr_assoc === true) {
                            $rex_params = array_slice($matches,1);
                            $params = array();
                            foreach($rex_params as $i => $p) {
                                $params[] = (is_string($i)) ? $i : $p;
                            }
                            $this->params = $params;
                        }
                        else $this->params = array_slice($matches,1);
                        
                        $this->paramsToAssoc();
                        
                        return true;
                        break;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Delete a specific regex or all regex
     *
     * @param string $regex
     */
    public function deleteRegex($regex = null)
    {
        if(isset($regex)) unset($this->_regex[$regex]);
        else $this->_regex = array();
    }
}