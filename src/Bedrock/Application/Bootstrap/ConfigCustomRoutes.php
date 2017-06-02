<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\Routing;
use Peak\Common\Collection;
use Peak\Common\DataException;
use Peak\Routing\Request;
use Peak\Routing\CustomRoute;

/**
 * Application Bootstrap Customer routes
 */
class ConfigCustomRoutes
{
    /**
     * Configure Application routes based on Application config
     *
     * @param \Peak\Bedrock\Application\Config  $config
     * @param \Peak\Bedrock\Application\Routing $routing
     */
    public function __construct(Config $config, Routing $routing)
    {
        $collection = new Collection();

        if (!empty($config->routes)) {
            foreach ($config->routes as $r) {
                if (isset($r['route'], $r['controller'], $r['action'])) {
                    $collection[] = new CustomRoute(
                        $r['route'],
                        $r['controller'],
                        $r['action']
                    );
                } elseif (is_string($r)) {
                    $parts = explode(' | ', $r);
                    if (count($parts) == 2) {
                        $ctrl_part = explode(Request::$separator, $parts[1]);

                        $collection[] = new CustomRoute(
                            trim($parts[0]),  // route
                            $ctrl_part[0],    // controller
                            (isset($ctrl_part[1]) ? $ctrl_part[1] : '') // action
                        );
                    } else {
                        throw new DataException('Invalid routing expression in your application config', $r);
                    }
                } else {
                    throw new DataException('Invalid routing in your application config', $r);
                }
            }
        }

        $routing->custom_routes = $collection;
    }
}
