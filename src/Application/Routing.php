<?php
namespace Peak\Application;

use Peak\Application;
use Peak\Collection;

use Peak\Exception;
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
     * @var  Peak\Routing\Request
     */
    public $request;

    /**
     * Final application route
     * @var Peak\Routing\Route
     */
    public $route;

    /**
     * Application regex routes collection from app config
     * @var Peak\Collection
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
        $this->loadAppCustomRoutes();
        $this->loadRequest($request);
        $this->base_uri = (isset($base_uri)) ? $base_uri : Application::conf('path.public');
    }

    /**
     * Load a request or use server request uri
     * 
     * @param  string|null $request       
     */
    public function loadRequest($request = null)
    {
        if(isset($request)) {
            $this->request = new Request($request, $this->base_uri);
        }
        else {
            $this->request = new RequestServerURI($this->base_uri);
        }
    }

    /**
     * Resolve application route
     * 
     * @return Peak\Routing\Route
     */
    public function getRoute()
    {
        $resolver = new RequestResolver($this->request);

        $this->route = $resolver->getRoute($this->custom_routes);

        return $this->route;
    }

    /**
     * Check app config for custom regex route
     */
    protected function loadAppCustomRoutes()
    {
        $regex = Application::conf('routing');

        $collection = new Collection();

        if(!empty($regex)) {
            foreach($regex as $r) {
                if(!isset($r['route']) || !isset($r['controller']) || !isset($r['action'])) {
                    throw new Exception('ERR_CUSTOM', 'Invalid routing in your application config');
                }
                else {
                    $collection[] = new CustomRoute(
                        $r['route'],
                        $r['controller'],
                        $r['action']
                    );
                }
            }
        }

        $this->custom_routes = $collection;
    }
}