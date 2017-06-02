<?php

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application;
use Peak\Common\Collection;
use Peak\Routing\Request;
use Peak\Routing\RequestServerURI;
use Peak\Routing\RequestResolver;
use Peak\Routing\Route;
use Peak\Routing\CustomRoute;

/**
 * Application Routing
 */
class Routing
{
    /**
     * Current application request
     * @var \Peak\Routing\Request
     */
    public $request;

    /**
     * Final application route
     * @var \Peak\Routing\Route
     */
    public $route;

    /**
     * Application regex routes collection from app config
     * @var \Peak\Common\Collection
     */
    public $custom_routes;

    /**
     * Application base uri.
     * By default, it is set to application public path config
     * @var string
     */
    public $base_uri;

    /**
     * Constructor
     *
     * @param mixed $request
     */
    public function __construct($request = null, $base_uri = null)
    {
        if (isset($request)) {
            $this->loadRequest($request);
        }

        $this->base_uri = (isset($base_uri)) ? $base_uri : relativePath(Application::conf('path.public'));
        $this->custom_routes = new Collection();
    }

    /**
     * Load a request or use server request uri
     *
     * @param  string|null $request
     * @return $this
     */
    public function loadRequest($request = null)
    {
        if (isset($request)) {
            $this->request = new Request($request, $this->base_uri);
            return $this;
        }

        $this->request = new RequestServerURI($this->base_uri);
        return $this;
    }

    /**
     * Resolve application route
     *
     * @return \Peak\Routing\Route
     */
    public function getRoute()
    {
        $resolver = new RequestResolver($this->request);

        $this->route = $resolver->getRoute($this->custom_routes);

        return $this->route;
    }
}
