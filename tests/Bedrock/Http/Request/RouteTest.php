<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\Request\Route;
use \Peak\Bedrock\Http\StackInterface;
use \Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouteTest
 */
class RouteTest extends TestCase
{
    public function testCreate()
    {
        $route = new Route(
            'GET',
            '/mypath',
            $this->createMock(StackInterface::class)
        );

        $this->assertTrue('GET' === $route->getMethod());
        $this->assertTrue('/mypath' === $route->getPath());
    }

    public function testMatch()
    {
        $route = new Route(
            'GET',
            '/mypath',
            $this->createMock(StackInterface::class)
        );

        $request = $this->createMock(ServerRequestInterface::class);

        $result = $route->match($request);
        $this->assertFalse($result);
    }
}
