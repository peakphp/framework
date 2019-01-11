<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Stack;
use \Psr\Http\Message\ServerRequestInterface;
use \Peak\Http\Request\HandlerResolver;

/**
 * Class StackTest
 */
class StackTest extends TestCase
{
    /**
     * @expectedException \Peak\Http\Exception\EmptyStackException
     */
    public function testCreateException()
    {
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

    /**
     * @expectedException \Peak\Http\Request\Exception\InvalidHandlerException
     */
    public function testProcessException()
    {
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
}