<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock;

use Peak\Bedrock\Http\Application;
use Peak\Blueprint\Http\Route;
use Peak\Blueprint\Http\Stack;
use Peak\Http\Request\PreRoute;

class AppRoutesMapper
{
    /**
     * @param Application $app
     * @return array
     */
    public function inspect(Application $app): array
    {
        return $this->inspectRecursive($app->getHandlers());
    }

    /**
     * @param array $handlers
     * @return array
     */
    private function inspectRecursive(array $handlers): array
    {
        $routes = [];
        foreach ($handlers as $handler) {
            $subRoutes = [];
            if ($handler instanceof Route && !($handler instanceof PreRoute)) {
                $route = [
                    'method' => $handler->getMethod(),
                    'path' => $handler->getPath(),
                    'stack' => [],
                ];
                $handlers = $handler->getHandlers();
                foreach ($handlers as $h) {
                    if (is_string($h)) {
                        $route['stack'][] = $h;
                    } elseif (is_object($h)) {
                        $route['stack'][] = get_class($h);
                    }
                }
                $routes[] = $route;
                $subRoutes = $this->inspectRecursive($handler->getHandlers());
            } elseif (is_array($handler)) {
                $subRoutes = $this->inspectRecursive($handler);
            } elseif ($handler instanceof Stack) {
                $subRoutes = $this->inspectRecursive($handler->getHandlers());
            }

            if (!empty($subRoutes)) {
                $routes = array_merge($subRoutes, $routes);
            }
        }

        //reorder routes by path
        $array = array_column($routes, 'path');
        array_multisort ($array, SORT_ASC, $routes);
        return $routes;
    }
}
