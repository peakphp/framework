<?php

declare(strict_types=1);

namespace Peak\Backpack\Bootstrap;

use Exception;
use Peak\Bedrock\Http\Application;
use Peak\Blueprint\Common\Bootable;
use function is_array;

class Routing implements Bootable
{
    private Application $app;

    private string $routesPropName;

    private string $routesPathPrefixPropName;

    /**
     * Routing constructor.
     * @param Application $app
     * @param string $routesPropName
     * @param string $routesPathPrefixPropName
     */
    public function __construct(
        Application $app,
        string $routesPropName = 'routes',
        string $routesPathPrefixPropName = 'routes_path_prefix'
    ) {
        $this->app = $app;
        $this->routesPropName = $routesPropName;
        $this->routesPathPrefixPropName = $routesPathPrefixPropName;
    }

    /**
     * Look for routes to register in application properties
     * @throws Exception
     */
    public function boot()
    {
        if ($this->app->getProps() === null) {
            return;
        }

        $routes = [];
        $pathPrefix = $this->app->getProp($this->routesPathPrefixPropName, '');
        $propRoutes = $this->app->getProp($this->routesPropName, []);

        if (!is_array($propRoutes)) {
            throw new Exception('Routes definitions must be an array!');
        }

        foreach ($this->app->getProp($this->routesPropName, []) as $route) {
            $this->validate($route);
            $routes[] = $this->app->createRoute(
                $route['method'] ?? null,
                $pathPrefix.$route['path'],
                $route['stack']
            );
        }

        $this->app->stack($routes);
    }

    /**
     * @param mixed $route
     * @throws Exception
     */
    private function validate($route)
    {
        if (!is_array($route)) {
            throw new Exception('Route definition must be an array!');
        } elseif (!isset($route['path'])) {
            throw new Exception('Route definition must have a path!');
        } elseif (!isset($route['stack'])) {
            throw new Exception('Route definition must have a stack!');
        }
    }
}
