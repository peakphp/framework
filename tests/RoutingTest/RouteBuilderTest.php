<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\Request;
use Peak\Routing\RouteBuilder;

class RouteBuilderTest extends TestCase
{

    function setUp()
    {
        Request::$separator = '/';
    }

    function testCreateNewRoute()
    {
        $route = RouteBuilder::get('ctrl', 'action');
        $this->assertTrue($route->controller === 'ctrl');
        $this->assertTrue($route->action === 'action');
        $this->assertTrue(empty($route->params));


        $route = RouteBuilder::get('ctrl', 'action', '');
        $this->assertTrue($route->controller === 'ctrl');
        $this->assertTrue($route->action === 'action');
        $this->assertTrue(empty($route->params));

    }

    function testCreateNewRouteWithParams()
    {
        
        $route = RouteBuilder::get('ctrl', 'action', 'test', 'test2');

        $this->assertFalse(empty($route->params));
        $this->assertTrue(count($route->params) == 2);


        $route = RouteBuilder::get('ctrl', 'action', ['test', 'test2']);

        $this->assertFalse(empty($route->params));
        $this->assertTrue(count($route->params) == 4);


        $route = RouteBuilder::get('ctrl', 'action', ['test' => 'test2']);

        $this->assertFalse(empty($route->params));
        $this->assertTrue(count($route->params) == 2);


        $route = RouteBuilder::get('ctrl', 'action', ['test' => 'test2'], ['test' => 'test2']);

        $this->assertFalse(empty($route->params));
        $this->assertTrue(count($route->params) == 4);


        $route = RouteBuilder::get('ctrl', 'action', 'test','test2','this','funky','extra');

        $this->assertFalse(empty($route->params));
        $this->assertTrue(count($route->params) == 5);
    }


}