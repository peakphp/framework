<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\JsonProcessor;

class JsonProcessorTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\ProcessorException
     */
    public function testException1()
    {
        $callableProcessor = new JsonProcessor();
        $callableProcessor->process('bad json');
    }
}
