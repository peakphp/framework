<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\Route;
use \Peak\Blueprint\Http\Stack;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\UriInterface;

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
            $this->createMock(Stack::class)
        );

        $this->assertTrue('GET' === $route->getMethod());
        $this->assertTrue('/mypath' === $route->getPath());
    }

    public function testMatch()
    {
        $route = new Route(
            'GET',
            '/mypath',
            $this->createMock(Stack::class)
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $result = $route->match($request);
        $this->assertFalse($result);
    }

    public function testMatch2()
    {
        $route = new Route(
            'GET',
            '/mypath',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/mypath');
        $result = $route->match($request);
        $this->assertTrue($result);
    }


    public function testMatch3()
    {
        $route = new Route(
            'POST',
            '/mypath',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/mypath');
        $result = $route->match($request);
        $this->assertFalse($result);
    }

    public function testMatch4()
    {
        $route = new Route(
            null,
            '/mypath',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/mypath');
        $result = $route->match($request);
        $this->assertTrue($result);
    }

    public function testMatch5()
    {
        $route = new Route(
            'GET',
            '/mypath',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/mypath2');
        $result = $route->match($request);
        $this->assertFalse($result);
    }

    public function testMatchRegex()
    {
        $route = new Route(
            'GET',
            '/mypath/[0-9]{2}',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/mypath/01');
        $result = $route->match($request);
        $this->assertTrue($result);
    }

    public function testMatch6()
    {
        $route = new Route(
            'GET',
            '/',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/');
        $result = $route->match($request);
        $this->assertTrue($result);

        $route = new Route(
            'GET',
            '',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/');
        $result = $route->match($request);
        $this->assertTrue($result);
    }

    public function testMatch7()
    {
        $route = new Route(
            'GET',
            '/my/path',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/my/path/');
        $result = $route->match($request);
        $this->assertTrue($result);

        $request = $this->createRequest('GET', '/my/path');
        $result = $route->match($request);
        $this->assertTrue($result);
    }

    public function testProcess()
    {
        $route = new Route(
            'GET',
            '/mypath',
            $this->createMock(Stack::class)
        );

        $request = $this->createRequest('GET', '/mypath');
        $result = $route->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }


    private function createRequest($method, $path)
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $request->method('getMethod')
            ->will($this->returnValue($method));

        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')
            ->will($this->returnValue($path));

        $request->method('getUri')
            ->will($this->returnValue($uri));

        return $request;
    }
}
