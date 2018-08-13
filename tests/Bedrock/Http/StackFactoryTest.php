<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\StackFactory;
use \Peak\Bedrock\Http\StackInterface;
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
        $this->assertInstanceOf(StackInterface::class, $stack);

        $stack = $stackFactory->create(['stuff', 'stuff'], $this->createMock(HandlerResolver::class));
        $this->assertInstanceOf(StackInterface::class, $stack);
    }
}
