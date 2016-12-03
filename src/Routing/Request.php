<?php
namespace Peak\Routing;

class Request
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
     * Final request
     * @var string
     */
    public $request_uri;

    /**
     * Request char separator
     */
    public static $separator = '/';

    /**
     * Set base url of your application
     *
     * @param  string $request_uri
     * @param  string $base_uri
     */
    public function __construct($request_uri, $base_uri = null)
    {
        if(!isset($base_uri)) $base_uri = self::$separator;
        $this->setBaseUri($base_uri);
        $this->setRequestUir($request_uri);
    }

    /**
     * Set the base of url request
     *
     * @param string $base_uri
     */
    public function setBaseUri($base_uri)
    {               
        //fix self::$separator missing at left and right of $base_uri
        $base_uri = trim($base_uri);
        if(substr($base_uri, 0, 1) !== self::$separator) $base_uri = self::$separator.$base_uri;
        if(substr($base_uri, -1, 1) !== self::$separator) $base_uri = $base_uri.self::$separator;
        $this->base_uri = $base_uri;
    }

    /**
     * Set request uri
     * 
     * @param string $request_uri
     */
    public function setRequestUir($request)
    {
        $this->raw_uri = $request;

        $request = $this->_standardize($request);

        if($this->base_uri !== self::$separator) {
            $request = str_ireplace($this->base_uri, '', $request);
        }

        $this->request_uri = $this->_standardize($request);
    }

    /**
     * Standardize the request
     * 
     * @param  string $request 
     * @return string         
     */
    private function _standardize($request)
    {
        $request = trim($request);
        
        if(substr($request, 0, 1) !== self::$separator) {
            $request = self::$separator.$request;
        }
        if(substr($request, -1, 1) !== self::$separator) {
            $request = $request.self::$separator;
        }

        $request = $this->_removeDoubleSeparator($request);

        return $request;
    }

    private function _removeDoubleSeparator($request)
    {
        return str_ireplace(
            self::$separator.''.self::$separator, 
            self::$separator, 
            $request
        );
    }
}