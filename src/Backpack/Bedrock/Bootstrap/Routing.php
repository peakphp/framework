<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock\Bootstrap;

use Peak\Bedrock\Application\Application;
use Peak\Blueprint\Common\Bootable;

/**
 * Class Routing
 * @package Peak\Backpack\Bedrock\Bootstrap
 */
class Routing implements Bootable
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var string
     */
    private $routesPropName;

    /**
     * @var string
     */
    private $routesPathPrefixPropName;

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
     * @throws \Exception
     */
    public function boot()
    {
        $routes = [];
        $pathPrefix = $this->app->getProp($this->routesPathPrefixPropName, '');

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
     * @param $route
     * @throws \Exception
     */
    private function validate($route)
    {
        if (!is_array($route)) {
            throw new \Exception('Route definition must be an array!');
        } elseif (!isset($route['path'])) {
            throw new \Exception('Route definition must have a path!');
        } elseif (!isset($route['stack'])) {
            throw new \Exception('Route definition must have a stack!');
        }
    }
}