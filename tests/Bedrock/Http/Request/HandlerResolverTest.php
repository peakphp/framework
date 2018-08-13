<?php

use \PHPUnit\Framework\TestCase;
use \Psr\Container\ContainerInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Peak\Bedrock\Http\Middleware\CallableMiddleware;
use \Peak\Bedrock\Http\Request\HandlerResolver;
use \Peak\Bedrock\Http\Stack;
use \Peak\Bedrock\Http\StackInterface;
use \Peak\Di\Container;

require_once FIXTURES_PATH.'/application/HandlerA.php';
require_once FIXTURES_PATH.'/application/MiddlewareA.php';

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

        $this->assertInstanceOf(StackInterface::class, $handlerResolver->resolve(new Stack([MiddlewareA::class], $handlerResolver)));

        $this->assertInstanceOf(CallableMiddleware::class, $handlerResolver->resolve(function($server, $request) {}));
    }

    public function testResolverWithContainer()
    {
        $handlerResolver = new HandlerResolver($this->createMock(ContainerInterface::class));
        $this->assertInstanceOf(HandlerA::class, $handlerResolver->resolve(HandlerA::class));

        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('create')
            ->will($this->returnValue(new HandlerA()));

        $handlerResolver = new HandlerResolver($container);
        $this->assertInstanceOf(HandlerA::class, $handlerResolver->resolve(HandlerA::class));
    }

    /**
     * @expectedException \Peak\Bedrock\Http\Request\Exception\HandlerNotFoundException
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
        } catch(\Peak\Bedrock\Http\Request\Exception\HandlerNotFoundException $e) {
            $this->assertTrue("UnknownClass" === $e->getHandler());
        }
    }

    /**
     * @expectedException \Peak\Bedrock\Http\Request\Exception\UnresolvableHandlerException
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
        } catch(\Peak\Bedrock\Http\Request\Exception\UnresolvableHandlerException $e) {
            $this->assertTrue(is_array($e->getHandler()));
        }
    }
}
