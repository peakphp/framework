<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\CallableProcessor ;

class CallableProcessorTest extends TestCase
{
    public function testException1()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $callableProcessor = new CallableProcessor();
        $callableProcessor->process(array());
    }

    public function testException2()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $callableProcessor = new CallableProcessor();
        $callableProcessor->process(function() {
            return null;
        });
    }
}
