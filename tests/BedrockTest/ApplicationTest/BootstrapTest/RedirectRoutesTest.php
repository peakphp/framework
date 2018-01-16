<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Routing;
use Peak\Bedrock\Application\Bootstrap\RedirectRoutes;

class RedirectRoutesTest extends TestCase
{
    /**
     * Test bootstrap class
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testBootstrap()
    {
        $app = dummyApp();
        if(!Application::container()->has(RedirectRoutes::class)) {
            Application::create(RedirectRoutes::class);
        }
        $routing = Application::get(Routing::class);

        $app_cusmtom_routes = $routing->custom_routes;
        $this->assertTrue(count($app_cusmtom_routes) == 2);
        $this->assertTrue($app_cusmtom_routes[0]->action === 'home');
        $this->assertTrue($app_cusmtom_routes[1]->action === 'old_user_url');
    }
}