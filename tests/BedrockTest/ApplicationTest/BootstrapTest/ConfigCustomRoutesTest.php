<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Bootstrap\ConfigCustomRoutes;
use Peak\Bedrock\View;
use Peak\Bedrock\View\Render\Layouts;

class ConfigCustomRoutesTest extends TestCase
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
        //Application::instantiate(ConfigCustomRoutes::class); //already called when creating app
        $kernel = Application::kernel();
        $routing = $kernel->routing;
        print_r($routing);
        //$this->assertTrue(count($routing->custom_routes) == 3);
        //$this->assertTrue($routing->custom_routes[0]->controller === 'user');
    }
}