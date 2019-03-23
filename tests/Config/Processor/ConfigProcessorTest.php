<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\ConfigProcessor;

class ConfigProcessorTest extends TestCase
{
    public function testException()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorTypeException::class);
        $processor = new ConfigProcessor();
        $processor->process([]);
    }
}
