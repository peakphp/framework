<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\ConfigData;
use Peak\Config\Processor\JsonProcessor;

class ConfigDataTest extends TestCase
{
    public function testJsonString()
    {
        $data = (new ConfigData(
            '{"foo": "bar2", "bar" : "foo"}',
            new JsonProcessor())
        )->get();
        
        $this->assertTrue(is_array($data));
        $this->assertTrue(array_key_exists('foo', $data));
    }
}