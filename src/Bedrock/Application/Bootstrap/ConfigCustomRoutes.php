<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\Routing;
use Peak\Common\Collection;
use Peak\Common\DataException;
use Peak\Routing\CustomRouteBuilder;

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
                if (is_array($r)) {
                    $collection[] = CustomRouteBuilder::createFromArray($r);
                } elseif (is_string($r)) {
                    $collection[] = CustomRouteBuilder::createFromString($r);
                } else {
                    throw new DataException('Invalid custom route type in your application config', $r);
                }
            }
        }

        $routing->custom_routes = $collection;
    }
}
