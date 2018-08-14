<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\CallableProcessor ;

class CallableProcessorTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\ProcessorException
     */
    public function testException1()
    {
        $callableProcessor = new CallableProcessor();
        $callableProcessor->process(array());
    }

    /**
     * @expectedException \Peak\Config\Exception\ProcessorException
     */
    public function testException2()
    {
        $callableProcessor = new CallableProcessor();
        $callableProcessor->process(function() {
            return null;
        });
    }
}
