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
        if (!isset($base_uri)) $base_uri = self::$separator;
        $this->setBaseUri($base_uri);
        $this->setRequestUri($request_uri);
    }

    /**
     * Set the base of url request
     *
     * @param string $base_uri
     */
    public function setBaseUri($base_uri)
    {               
        $this->base_uri = $this->standardize($base_uri);
    }

    /**
     * Set request uri
     *
     * @param string|array $request_uri
     */
    public function setRequestUri($request)
    {
        $this->raw_uri = $request;

        if (is_array($request)) {
            $request = $this->requestArrayToString($request);
        }

        $request = $this->standardize($request);

        if ($this->base_uri !== self::$separator) {
            $request = str_ireplace($this->base_uri, '', $request);
        }

        $this->request_uri = $this->standardize($request);
    }

    /**
     * Standardize the request
     *
     * @param  string $request 
     * @return string         
     */
    protected function standardize($request)
    {
        $request = trim($this->removeDoubleSeparator($request));

        if (substr($request, 0, 1) !== self::$separator) {
            $request = self::$separator.$request;
        }
        if (substr($request, -1, 1) !== self::$separator) {
            $request = $request.self::$separator;
        }

        $request = $this->removeDoubleSeparator($request);

        return $request;
    }

    /**
     * Remove double separator ex: double //
     *
     * @param  string $request
     * @return string         
     */
    protected function removeDoubleSeparator($request)
    {
        return str_ireplace(
            self::$separator.self::$separator, 
            self::$separator, 
            $request
        );
    }

    /**
     * Transform an array to a string
     *
     * @param  array  $request
     * @return string
     */
    protected function requestArrayToString($request)
    {
        return implode(self::$separator, $request);
    }
}
