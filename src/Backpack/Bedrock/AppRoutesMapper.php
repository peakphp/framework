<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock;

use Peak\Bedrock\Http\Application;
use Peak\Blueprint\Http\Route;
use Peak\Blueprint\Http\Stack;

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
            if ($handler instanceof Route) {
                $routes[] = [
                    'method' => $handler->getMethod(),
                    'path' => $handler->getPath(),
                ];
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
        return $routes;
    }
}
