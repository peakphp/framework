<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock\Bootstrap;

use Peak\Bedrock\Application\Application;
use Peak\Blueprint\Common\Bootable;

class Routing implements Bootable
{
    /**
     * @var Application
     */
    private $app;

    /**
     * Routing constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Look for "routes" config in application properties
     * @throws \Exception
     */
    public function boot()
    {
        if ($this->app->hasProp('routes')) {
            $routes = [];
            foreach ($this->app->getProp('routes') as $route) {
                $this->validate($route);
                $route[] = $this->app->createRoute(
                    $route['method'] ?? 'GET',
                    $route['path'],
                    $route['stack']
                );
            }
            $this->app->stack($routes);
        }
    }

    /**
     * @param $route
     * @throws \Exception
     */
    private function validate($route)
    {
        if (!is_array($route)) {
            throw new \Exception('Route definition must be an array!');
        } elseif ($route['path']) {
            throw new \Exception('Route definition must have a path!');
        } elseif ($route['stack']) {
            throw new \Exception('Route definition must have a stack!');
        }
    }
}