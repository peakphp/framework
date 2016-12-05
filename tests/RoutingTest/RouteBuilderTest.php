<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\RouteBuilder;

/**
 * @package    Peak\Resolve
 */
class RouteBuilderTest extends TestCase
{

    function testCreateNewRoute()
    {
        $route = RouteBuilder::get('ctrl', 'action');
        $this->assertTrue($route->controller === 'ctrl');
        $this->assertTrue($route->action === 'action');
        $this->assertTrue(empty($route->params));
        $this->assertTrue(empty($route->params_assoc));
    }


}