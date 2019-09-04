<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\RouteArgs;

class RouteArgsTest extends TestCase
{
    public function testGeneral()
    {
        $routeArgs = new RouteArgs(['test' => 'bar']);
        $this->assertTrue($routeArgs->raw() === ['test' => 'bar']);
        $this->assertTrue(isset($routeArgs->test));
        $this->assertTrue($routeArgs->test === 'bar');
        $this->assertFalse(isset($routeArgs->bar));
        $this->assertTrue(isset($routeArgs['test']));
        $this->assertTrue($routeArgs['test'] === 'bar');
        $this->assertFalse(isset($routeArgs[1]));
    }
}
