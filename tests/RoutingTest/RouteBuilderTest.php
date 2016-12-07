<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\Request;
use Peak\Routing\RouteBuilder;

/**
 * @package    Peak\Resolve
 */
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
        $this->assertTrue(empty($route->params_assoc));


        $route = RouteBuilder::get('ctrl', 'action', '');
        $this->assertTrue($route->controller === 'ctrl');
        $this->assertTrue($route->action === 'action');
        $this->assertTrue(empty($route->params));
        $this->assertTrue(empty($route->params_assoc));

    }

    function testCreateNewRouteWithParams()
    {
        
        $route = RouteBuilder::get('ctrl', 'action', 'test', 'test2');

        $this->assertFalse(empty($route->params));
        $this->assertFalse(empty($route->params_assoc));
        $this->assertTrue(count($route->params) == 2);
        $this->assertTrue($route->params_assoc['test'] === 'test2');


        $route = RouteBuilder::get('ctrl', 'action', ['test', 'test2']);

        $this->assertFalse(empty($route->params));
        $this->assertFalse(empty($route->params_assoc));
        $this->assertTrue(count($route->params) == 4);
        $this->assertTrue($route->params_assoc[0] === 'test');


        $route = RouteBuilder::get('ctrl', 'action', ['test' => 'test2']);

        $this->assertFalse(empty($route->params));
        $this->assertFalse(empty($route->params_assoc));
        $this->assertTrue(count($route->params) == 2);
        $this->assertTrue($route->params_assoc['test'] === 'test2');


        $route = RouteBuilder::get('ctrl', 'action', ['test' => 'test2'], ['test' => 'test2']);

        $this->assertFalse(empty($route->params));
        $this->assertFalse(empty($route->params_assoc));
        $this->assertTrue(count($route->params) == 4);
        $this->assertTrue(count($route->params_assoc) == 1);


        $route = RouteBuilder::get('ctrl', 'action', 'test','test2','this','funky','extra');

        $this->assertFalse(empty($route->params));
        $this->assertFalse(empty($route->params_assoc));
        $this->assertTrue(count($route->params) == 5);
        $this->assertTrue(count($route->params_assoc) == 2);
    }


}