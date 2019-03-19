<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\StdClassProcessor;

class StdClassProcessorTest extends TestCase
{
    public function testException1()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $callableProcessor = new StdClassProcessor();
        $callableProcessor->process([]);
    }
}
