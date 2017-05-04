<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\Route;

class RouteTest extends TestCase
{

    function testIsRoute()
    {
        $route = new Route();

        $route->controller = 'index';
        $route->action = 'test';

        $this->assertTrue($route->is('index'));
        $this->assertTrue($route->is('index', 'test'));
        $this->assertFalse($route->is('index', 'otheraction'));
    }



}