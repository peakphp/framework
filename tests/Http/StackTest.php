<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Stack;
use \Psr\Http\Message\ServerRequestInterface;
use \Peak\Http\Request\HandlerResolver;

require_once FIXTURES_PATH . '/application/MiddlewareA.php';
require_once FIXTURES_PATH . '/application/HandlerA.php';
require_once FIXTURES_PATH . '/application/HandlerB.php';
require_once FIXTURES_PATH . '/application/HandlerC.php';

class StackTest extends TestCase
{
    public function testCreateException()
    {
        $this->expectException(\Peak\Http\Exception\EmptyStackException::class);
        new Stack([], $this->createMock(HandlerResolver::class));
    }

    public function testCreateExceptionGetStack()
    {
        try {
            new Stack([], $this->createMock(HandlerResolver::class));
        } catch(\Peak\Http\Exception\EmptyStackException $e) {
            $this->assertInstanceOf(Stack::class, $e->getStack());
        }
    }

    public function testProcessException()
    {
        $this->expectException(\Peak\Http\Request\Exception\InvalidHandlerException::class);
        $stack = new Stack([new \stdClass()], $this->createMock(HandlerResolver::class));
        $stack->handle($this->createMock(ServerRequestInterface::class));
    }


    public function testProcessExceptionGetHandler()
    {
        $handler = new \stdClass();
        $stack = new Stack([$handler], $this->createMock(HandlerResolver::class));
        try {
            $stack->handle($this->createMock(ServerRequestInterface::class));
        } catch(\Peak\Http\Request\Exception\InvalidHandlerException $e) {
            $this->assertInstanceOf(\stdClass::class, $e->getHandler());
        }
    }

    public function testEndWithoutResponse()
    {
        $this->expectException(\Peak\Http\Exception\StackEndedWithoutResponseException::class);
        $stack = new Stack([new MiddlewareA()], $this->createMock(HandlerResolver::class));
        $stack->handle($this->createMock(ServerRequestInterface::class));
    }

    public function testEndWithoutResponseGetStack()
    {
        $stack = new Stack([new MiddlewareA()], $this->createMock(HandlerResolver::class));
        try {
            $stack->handle($this->createMock(ServerRequestInterface::class));
        } catch(\Peak\Http\Exception\StackEndedWithoutResponseException $e) {
            $this->assertTrue($e->getStack() === $stack);
        }
    }


    public function testMultipleHandler()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $stack = new Stack([new HandlerA()], $this->createMock(HandlerResolver::class));
        $response = $stack->handle($request);
        $response = $stack->handle($request);
        $response = $stack->handle($request);

        $this->assertTrue(isset($response));
    }

    public function testGetParent()
    {
        $stack1 = new Stack([new HandlerA()], $this->createMock(HandlerResolver::class));
        $stack2 = new Stack([new HandlerA()], $this->createMock(HandlerResolver::class));

        $stack2->setParent($stack1);
        $this->assertTrue($stack1 === $stack2->getParent());
    }

    public function testProcessChildStack()
    {
        $stack1 = new Stack([new HandlerA()], $this->createMock(HandlerResolver::class));
        $stack2 = new Stack([$stack1], $this->createMock(HandlerResolver::class));

        $response = $stack2->handle($this->createMock(ServerRequestInterface::class));
        $this->assertTrue(true);
    }

    public function testProcessChildStack2()
    {
        $handler = new HandlerResolver(null);
        $stack1 = new Stack([
            new MiddlewareA()
        ], $handler);
        $stack2 = new Stack([
            $stack1,
            new MiddlewareA(),
            function($req, $next) {
                return $next->handle($req);
            },
            new HandlerA()
        ],$handler);

        $response = $stack2->handle($this->createMock(ServerRequestInterface::class));
        $this->assertTrue(true);
    }

    public function testCheckResponse()
    {
        $handlerResolver = new HandlerResolver(null);
        $stack1 = new Stack([ new MiddlewareA()], $handlerResolver);
        $stack2 = new Stack([
            $stack1,  $stack1,
            new Stack([new MiddlewareA()], $handlerResolver),
            new HandlerA()
        ], $handlerResolver);
        $response = $stack2->handle($this->createMock(ServerRequestInterface::class));
        $this->assertTrue($response->getMsg() === 'ResponseA');
    }

    public function testCheckResponse2()
    {
        $handlerResolver = new HandlerResolver(null);
        $stack1 = new Stack([ new MiddlewareA()], $handlerResolver);
        $stack2 = new Stack([
            $stack1,  $stack1,
            new Stack([new MiddlewareA(), new HandlerB()], $handlerResolver),
            new HandlerA()
        ], $handlerResolver);
        $response = $stack2->handle($this->createMock(ServerRequestInterface::class));
        $this->assertTrue($response->getMsg() === 'ResponseB');
    }

    public function testCheckResponse3()
    {
        $handlerResolver = new HandlerResolver(null);
        $stack1 = new Stack([ new MiddlewareA()], $handlerResolver);
        $stack2 = new Stack([
            $stack1,  new HandlerC(), $stack1,
            new Stack([new MiddlewareA(), new HandlerB()], $handlerResolver),
            new HandlerA()
        ], $handlerResolver);
        $response = $stack2->handle($this->createMock(ServerRequestInterface::class));
        $this->assertTrue($response->getMsg() === 'ResponseC');
    }

}
