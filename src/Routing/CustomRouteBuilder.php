<?php

declare(strict_types=1);

namespace Peak\Routing;

use Peak\Routing\Exception\InvalidCustomRouteException;

class CustomRouteBuilder
{
    /**
     * Create custom route from an array
     *
     * @param array $route
     * @return CustomRoute
     * @throws InvalidCustomRouteException
     */
    public static function createFromArray(array $route)
    {
        if (!isset($route['route'], $route['controller'], $route['action'])) {
            throw new InvalidCustomRouteException($route);
        }

        return new CustomRoute(
            $route['route'],
            $route['controller'],
            $route['action']
        );
    }

    /**
     * Create custom route from a string expression
     *
     * @example mypage/([0-9]*) | page/index
     *
     * @param string $expression
     * @return CustomRoute
     * @throws InvalidCustomRouteException
     */
    public static function createFromString($expression)
    {
        $parts = explode(' | ', $expression);
        if (count($parts) != 2) {
            throw new InvalidCustomRouteException($expression);
        }

        $ctrl_part = explode(Request::$separator, $parts[1]);

        return new CustomRoute(
            trim($parts[0]),  // route
            $ctrl_part[0],    // controller
            (isset($ctrl_part[1]) ? $ctrl_part[1] : '') // action
        );
    }
}
