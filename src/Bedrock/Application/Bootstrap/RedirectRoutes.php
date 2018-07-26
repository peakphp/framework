<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\Routing;
use Peak\Routing\CustomRouteBuilder;

/**
 * Class RedirectRoutes
 * @package Peak\Bedrock\Application\Bootstrap
 */
class RedirectRoutes
{
    /**
     * RedirectRoutes constructor.
     *
     * @param Config $config
     * @param Routing $routing
     * @throws \Peak\Routing\Exception\InvalidCustomRouteException
     */
    public function __construct(Config $config, Routing $routing)
    {
        if (isset($config->redirects)) {
            $controller = 'redirect';
            if (isset($config->redirects_controller)) {
                $controller = $config->redirects_controller;
            }
            foreach ($config->redirects as $action => $redirect_route) {
                $routing->custom_routes[] = CustomRouteBuilder::createFromString(
                    $redirect_route['route'].' | '.$controller.'/'.$action
                );
            }
        }
    }
}
