<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\Stack;
use \Peak\Bedrock\Http\StackInterface;
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
}
