<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\StdClassProcessor;

class StdClassProcessorTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\ProcessorException
     */
    public function testException1()
    {
        $callableProcessor = new StdClassProcessor();
        $callableProcessor->process([]);
    }
}
