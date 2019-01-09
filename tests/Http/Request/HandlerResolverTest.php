<?php

use \PHPUnit\Framework\TestCase;
use \Psr\Container\ContainerInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Peak\Http\Middleware\CallableMiddleware;
use \Peak\Http\Request\HandlerResolver;
use \Peak\Http\Stack;
use \Peak\Di\Container;

require_once FIXTURES_PATH . '/application/HandlerA.php';
require_once FIXTURES_PATH . '/application/MiddlewareA.php';

/**
 * Class HandlerResolverTest
 */
class HandlerResolverTest extends TestCase
{
    public function testResolve()
    {
        $handlerResolver = new HandlerResolver(null);

        $this->assertInstanceOf(HandlerA::class, $handlerResolver->resolve(HandlerA::class));
        $this->assertInstanceOf(HandlerA::class, $handlerResolver->resolve(new HandlerA()));
        $this->assertInstanceOf(RequestHandlerInterface::class, $handlerResolver->resolve(new HandlerA()));

        $this->assertInstanceOf(MiddlewareA::class, $handlerResolver->resolve(MiddlewareA::class));
        $this->assertInstanceOf(MiddlewareA::class, $handlerResolver->resolve(new MiddlewareA()));
        $this->assertInstanceOf(MiddlewareInterface::class, $handlerResolver->resolve(new MiddlewareA()));

        $this->assertInstanceOf(\Peak\Blueprint\Http\Stack::class, $handlerResolver->resolve(new Stack([MiddlewareA::class], $handlerResolver)));

        $this->assertInstanceOf(CallableMiddleware::class, $handlerResolver->resolve(function($server, $request) {}));
    }

    public function testResolverWithContainerHasGet()
    {
        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('has')
            ->with(HandlerA::class)
            ->will($this->returnValue(new HandlerA()));

        $container->expects($this->once())
            ->method('get')
            ->will($this->returnValue(new HandlerA()));

        $handlerResolver = new HandlerResolver($container);
        $this->assertInstanceOf(HandlerA::class, $handlerResolver->resolve(HandlerA::class));
    }

    public function testResolverWithPeakContainer()
    {
        $handlerResolver = new HandlerResolver(new Container());
        $this->assertInstanceOf(HandlerA::class, $handlerResolver->resolve(HandlerA::class));
    }

    /**
     * @expectedException \Peak\Http\Request\Exception\HandlerNotFoundException
     */
    public function testResolverHandlerNotFoundException()
    {
        $handlerResolver = new HandlerResolver(null);
        $handlerResolver->resolve("UnknownClass");
    }

    public function testResolverHandlerNotFoundExceptionGetHandler()
    {
        $handlerResolver = new HandlerResolver(null);
        try {
            $handlerResolver->resolve("UnknownClass");
        } catch(\Peak\Http\Request\Exception\HandlerNotFoundException $e) {
            $this->assertTrue("UnknownClass" === $e->getHandler());
        }
    }

    /**
     * @expectedException \Peak\Http\Request\Exception\UnresolvableHandlerException
     */
    public function testResolverUnresolvableHandlerException()
    {
        $handlerResolver = new HandlerResolver(null);
        $handlerResolver->resolve(array());
    }

    public function testResolverUnresolvableHandlerExceptionGetHandler()
    {
        $handlerResolver = new HandlerResolver(null);
        try {
            $handlerResolver->resolve(array());
        } catch(\Peak\Http\Request\Exception\UnresolvableHandlerException $e) {
            $this->assertTrue(is_array($e->getHandler()));
        }
    }
}
