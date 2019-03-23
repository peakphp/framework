<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Stream\XmlStream;

class XmlStreamTest extends TestCase
{
    public function testProcess()
    {
        $xmlStream = new XmlStream('<config><data>test</data></config>');
        $this->assertTrue($xmlStream->get() === ['data' => 'test']);
    }
}
