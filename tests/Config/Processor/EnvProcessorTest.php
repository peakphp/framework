<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\CollectionProcessor;

class EnvProcessorTest extends TestCase
{
    public function testException1()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $callableProcessor = new \Peak\Config\Processor\EnvProcessor();
        $callableProcessor->process('/sda/sasd/::+A"');
    }
}
