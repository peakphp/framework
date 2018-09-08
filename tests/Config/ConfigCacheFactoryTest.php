<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Cache\FileCache;
use Peak\Config\ConfigFactory;
use Peak\Config\ConfigCacheFactory;

class ConfigCacheFactoryTest extends TestCase
{
    /**
     * @throws \Peak\Config\Exception\CachePathNotFoundException
     * @throws \Peak\Config\Exception\CachePathNotWritableException
     * @throws \Peak\Config\Exception\UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    function testLoadConfig()
    {
        $now = date('Y-m-d H:i:s');

        $configCacheFactory = new ConfigCacheFactory(
            'my-conf-id',
            3600,
            new ConfigFactory(),
            new FileCache(__DIR__)
        );

        $config = $configCacheFactory->loadResources([
            FIXTURES_PATH.'/config/arrayfile1.php',
            FIXTURES_PATH.'/config/arrayfile2.php',
            [
                'now' => $now
            ]
        ]);

        $this->assertTrue($config->now === $now);
        $this->assertTrue($config instanceof \Peak\Blueprint\Config\Config);

        $newNow = 'randomstuff';

        $config = $configCacheFactory->loadResources([
            FIXTURES_PATH.'/config/arrayfile1.php',
            FIXTURES_PATH.'/config/arrayfile2.php',
            [
                'now' => $newNow
            ]
        ]);

        $this->assertTrue($config->now !== $newNow);
        $this->assertTrue($config instanceof \Peak\Blueprint\Config\Config);

        // delete cached file(s)
        foreach (glob(__DIR__.'/*.ser') as $file) {
            unlink($file);
        }
    }

}