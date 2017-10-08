<?php

namespace Peak\Routing;

use Peak\Common\DataException;

class CustomRouteBuilder
{
    /**
     * Create custom route from an array
     *
     * @param array $route
     * @return CustomRoute
     * @throws DataException
     */
    public static function createFromArray(array $route)
    {
        if (!isset($route['route'], $route['controller'], $route['action'])) {
            throw new DataException('Invalid custom route array expression', $route);
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
     * @throws DataException
     */
    public static function createFromString($expression)
    {
        $parts = explode(' | ', $expression);
        if (count($parts) != 2) {
            throw new DataException('Invalid custom route string expression', $expression);
        }

        $ctrl_part = explode(Request::$separator, $parts[1]);

        return new CustomRoute(
            trim($parts[0]),  // route
            $ctrl_part[0],    // controller
            (isset($ctrl_part[1]) ? $ctrl_part[1] : '') // action
        );
    }
}
