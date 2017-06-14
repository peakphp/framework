<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application\Routing;
use Peak\Routing\Route;

class RoutingTest extends TestCase
{

    function testLoadRequest()
    {
        $routing = new Routing('foo/bar', '/foo');
        $this->assertTrue($routing->request->base_uri === '/foo/');
        $this->assertTrue($routing->base_uri === '/foo/');
        $this->assertTrue($routing->request->raw_uri == 'foo/bar');
        $this->assertTrue($routing->request->request_uri === '/bar/');
    }

    function testGetRoute()
    {
        $routing = new Routing('foo/bar', '/foo');
        $route = $routing->getRoute();
        $this->assertTrue($route instanceof Route);
        $this->assertTrue($route->base_uri === '/foo/');
        $this->assertTrue($route->request_uri === '/bar/');
        $this->assertTrue($route->raw_uri == 'foo/bar');
    }
}