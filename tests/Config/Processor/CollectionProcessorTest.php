<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\CollectionProcessor;

class CollectionProcessorTest extends TestCase
{
    public function testException1()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $callableProcessor = new CollectionProcessor();
        $callableProcessor->process([]);
    }
}
