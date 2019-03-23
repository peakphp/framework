<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Config;
use Peak\Config\Stream\DataStream;
use Peak\Config\Stream\FileStream;
use Peak\Config\ConfigResolver;
use Peak\Blueprint\Collection\Collection;

class ConfigResolverTest extends TestCase
{
    function getConfigsDataProvider()
    {
        $data = [
            'array' => [],
            'function' => function() {},
            'config' => $this->createMock(Config::class),
            'file1' => FIXTURES_PATH.'/config/cli.yml',
            'collection' => $this->createMock(Collection::class),
        ];

        return $data;
    }

    /**
     * @throws ReflectionException
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    function testLoadConfig()
    {
        $configs = $this->getConfigsDataProvider();
        $cr = new ConfigResolver(null);
        $this->assertInstanceOf(DataStream::class, $cr->resolve($configs['array']));
        $this->assertInstanceOf(DataStream::class, $cr->resolve($configs['function']));
        $this->assertInstanceOf(DataStream::class, $cr->resolve($configs['config']));
        $this->assertInstanceOf(FileStream::class, $cr->resolve($configs['file1']));
        $this->assertInstanceOf(DataStream::class, $cr->resolve($configs['collection']));
    }



}