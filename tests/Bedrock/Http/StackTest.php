<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\Stack;
use \Psr\Http\Message\ServerRequestInterface;
use \Peak\Bedrock\Http\Request\HandlerResolver;

/**
 * Class StackTest
 */
class StackTest extends TestCase
{
    /**
     * @expectedException \Peak\Bedrock\Http\Exception\EmptyStackException
     */
    public function testCreateException()
    {
        $stack = new Stack([], $this->createMock(HandlerResolver::class));
    }

    /**
     * @expectedException \Peak\Bedrock\Http\Request\Exception\InvalidHandlerException
     */
    public function testProcessException()
    {
        $stack = new Stack([new \stdClass()], $this->createMock(HandlerResolver::class));
        $stack->handle($this->createMock(ServerRequestInterface::class));
    }
}
