<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\Routing;
use Peak\Routing\CustomRouteBuilder;

/**
 * Manage redirection routes
 */
class RedirectRoutes
{
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
