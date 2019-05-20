<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Response\Emitter;

class EmitterTest extends TestCase
{
    public function testCreate()
    {
        $emitter = new Emitter();
        $result = $emitter->emit($this->createMock(\Psr\Http\Message\ResponseInterface::class));
        $this->assertTrue($result);
    }
}
