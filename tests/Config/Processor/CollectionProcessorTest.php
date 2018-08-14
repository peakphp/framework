<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\CollectionProcessor;

class CollectionProcessorTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\ProcessorException
     */
    public function testException1()
    {
        $callableProcessor = new CollectionProcessor();
        $callableProcessor->process([]);
    }
}
