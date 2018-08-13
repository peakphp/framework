<?php

use PHPUnit\Framework\TestCase;
use \Peak\Config\Stream\ConfigStream;
use \Peak\Config\ConfigInterface;

class ConfigStreamTest extends TestCase
{
    public function testStream()
    {
        $config = $this->createMock(ConfigInterface::class);
        $config->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue([]));

        $configStream = new ConfigStream($config);
        $this->assertTrue(is_array($configStream->get()));
    }
}