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

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->once()))
            ->method('getPath')
            ->will($this->returnValue('/mypath'));

        $request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($uri));

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

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

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

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->never())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->once()))
            ->method('getPath')
            ->will($this->returnValue('/mypath'));

        $request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($uri));

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

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->once()))
            ->method('getPath')
            ->will($this->returnValue('/mypath2'));

        $request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($uri));

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

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->once()))
            ->method('getPath')
            ->will($this->returnValue('/mypath/01'));

        $request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($uri));

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

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->once()))
            ->method('getPath')
            ->will($this->returnValue('/mypath'));

        $request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($uri));

        $result = $route->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
