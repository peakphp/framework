<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\StackFactory;
use \Peak\Bedrock\Http\Request\HandlerResolver;

/**
 * Class StackFactoryTest
 */
class StackFactoryTest extends TestCase
{
    public function testCreate()
    {
        $stackFactory = new StackFactory($this->createMock(HandlerResolver::class));

        $stack = $stackFactory->create(['stuff', 'stuff']);
        $this->assertInstanceOf(\Peak\Blueprint\Http\Stack::class, $stack);

        $stack = $stackFactory->create(['stuff', 'stuff'], $this->createMock(HandlerResolver::class));
        $this->assertInstanceOf(\Peak\Blueprint\Http\Stack::class, $stack);
    }
}
