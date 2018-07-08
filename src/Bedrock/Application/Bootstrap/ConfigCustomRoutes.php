<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\Routing;
use Peak\Common\Collection\Collection;
use Peak\Routing\CustomRouteBuilder;
use Peak\Routing\Exception\InvalidCustomRouteException;

/**
 * Application Bootstrap Customer routes
 */
class ConfigCustomRoutes
{
    /**
     * Configure Application routes based on Application config
     *
     * @param Config $config
     * @param Routing $routing
     * @throws InvalidCustomRouteException
     */
    public function __construct(Config $config, Routing $routing)
    {
        $collection = new Collection();

        if (!empty($config->routes)) {
            foreach ($config->routes as $r) {
                if (is_array($r)) {
                    $collection[] = CustomRouteBuilder::createFromArray($r);
                } elseif (is_string($r)) {
                    $collection[] = CustomRouteBuilder::createFromString($r);
                } else {
                    throw new InvalidCustomRouteException($r);
                }
            }
        }

        $routing->custom_routes = $collection;
    }
}
