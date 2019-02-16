<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\RouteParameter;

class RouteParameterTest extends TestCase
{
    public function testGeneral()
    {
        $routeParam = new RouteParameter(['test' => 'bar']);
        $this->assertTrue($routeParam->raw() === ['test' => 'bar']);
        $this->assertTrue($routeParam->test === 'bar');
        $this->assertTrue(isset($routeParam->test));
        $this->assertFalse(isset($routeParam->bar));
    }
}
