<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\CollectionProcessor;

class EnvProcessorTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\ProcessorException
     */
    public function testException1()
    {
        $callableProcessor = new \Peak\Config\Processor\EnvProcessor();
        $callableProcessor->process('/sda/sasd/::+A"');
    }
}
