<?php
namespace Peak\Application;

use Peak\Application;
use Peak\Collection;

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
    public $regex_collection;

    /**
     * Constructor
     *
     * @param mixed $request
     */
    public function __construct($request = null)
    {
        $this->loadAppCustomRoutes();
        $this->loadRequest($request);
    }

    /**
     * Load a request or use server request uri
     * 
     * @param  string|null $request       
     */
    public function loadRequest($request = null)
    {
        if(isset($request)) {
            $this->request = new Request($request, Application::conf('path.public'));
        }
        else {
            $this->request = new RequestServerURI(Application::conf('path.public'));
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

        $this->route = $resolver->getRoute($this->regex_collection);

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

        $this->regex_collection = $collection;
    }
}