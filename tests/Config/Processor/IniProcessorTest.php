<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\IniProcessor;

class IniProcessorTest extends TestCase
{
    public function testException1()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $processor = new IniProcessor();
        $processor->process('=s=s=s=s');
    }

    public function testException2()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $processor = new IniProcessor();
        $processor->process('[test:test]'."\n".'foo=1');
    }
}
