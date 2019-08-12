<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Backpack\ConfigLoader;

class ConfigLoaderTest extends TestCase
{
    public function testLoad()
    {
        $config = (new ConfigLoader())
            ->load([
                ['foo' => 'bar']
            ]);

        $this->assertInstanceOf(\Peak\Config\Config::class, $config);
    }

    public function testLoadWith()
    {
        $config = (new ConfigLoader())
            ->loadWith([
                ['foo' => 'bar']
            ], new CustomConfig());

        $this->assertInstanceOf(CustomConfig::class, $config);
    }

    public function testLoadWithCache()
    {
        $config = (new ConfigLoader())
            ->setCache(__DIR__, 'my-conf', 0)
            ->load([
                ['foo' => 'bar']
            ]);

        $this->assertInstanceOf(\Peak\Config\Config::class, $config);
    }

    public function testLoadWithConfigFactory()
    {
        $config = (new ConfigLoader())
            ->setConfigFactory(new \Peak\Config\ConfigFactory())
            ->load([
                ['foo' => 'bar']
            ]);

        $this->assertInstanceOf(\Peak\Config\Config::class, $config);
    }

    public function testLoadConfigFactoryError()
    {
        $config = @(new ConfigLoader())
            ->setCache(__DIR__, 'my-conf', 0)
            ->setConfigFactory(new \Peak\Config\ConfigFactory())
            ->load([
                ['foo' => 'bar']
            ]);

        $this->assertInstanceOf(\Peak\Config\Config::class, $config);
    }

    public function testSetCacheTrigger()
    {
        $config = @(new ConfigLoader())
            ->setConfigFactory(new \Peak\Config\ConfigFactory())
            ->setCache(__DIR__, 'my-conf', 0)
            ->load([
                ['foo' => 'bar']
            ]);
        $this->assertInstanceOf(\Peak\Config\Config::class, $config);
    }
}

class CustomConfig extends \Peak\Config\Config {}

function customeErrorHandler($errno, $errstr, $errfile, $errline) {
    echo implode('-', [$errno, $errfile, $errline]);
}