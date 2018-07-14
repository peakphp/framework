<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\ConfigCache;
use Peak\Config\ConfigFactory;
use Peak\Config\ConfigCacheFactory;
use Peak\Config\ConfigInterface;

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
            new ConfigFactory(),
            new ConfigCache(__DIR__)
        );

        $config = $configCacheFactory->loadResources('my-conf-id', 3600, [
            FIXTURES_PATH.'/config/arrayfile1.php',
            FIXTURES_PATH.'/config/arrayfile2.php',
            [
                'now' => $now
            ]
        ]);

        $this->assertTrue($config->now === $now);
        $this->assertTrue($config instanceof ConfigInterface);

        $newNow = 'randomstuff';

        $config = $configCacheFactory->loadResources('my-conf-id', 3600, [
            FIXTURES_PATH.'/config/arrayfile1.php',
            FIXTURES_PATH.'/config/arrayfile2.php',
            [
                'now' => $newNow
            ]
        ]);

        $this->assertTrue($config->now !== $newNow);
        $this->assertTrue($config instanceof ConfigInterface);

        // delete cached file(s)
        foreach (glob(__DIR__.'/*.ser') as $file) {
            unlink($file);
        }
    }

}