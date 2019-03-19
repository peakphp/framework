<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\JsonProcessor;

class JsonProcessorTest extends TestCase
{
    public function testException1()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $callableProcessor = new JsonProcessor();
        $callableProcessor->process('bad json');
    }
}
