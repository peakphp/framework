<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processor\YamlProcessor;

class YamlProcessorTest extends TestCase
{
    public function testNormal()
    {
        $processor = new YamlProcessor();
        $data = $processor->process('name: foobar');
        $this->assertTrue($data === ['name' => 'foobar']);
    }

    public function testException()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $processor = new YamlProcessor();
        $processor->process('<data>');
    }
}
