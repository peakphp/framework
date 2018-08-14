<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Cache\FileCache;
use Peak\Config\ConfigFactory;

class ConfigCacheTest extends TestCase
{
    /**
     * @throws \Peak\Config\Exception\CachePathNotFoundException
     * @throws \Peak\Config\Exception\CachePathNotWritableException
     * @throws \Peak\Config\Exception\UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    function testLoadConfig()
    {
        $configCache = new FileCache(__DIR__);
        $cached = false;
        $cacheId = 'my-configuration-id';

        if ($configCache->isExpired($cacheId)) {
            $cached = false;
            $configFactory = new ConfigFactory();
            $config = $configFactory->loadResources([
                FIXTURES_PATH.'/config/arrayfile1.php',
                FIXTURES_PATH.'/config/arrayfile2.php',
            ]);

            $configCache->set(
                $cacheId,
                $config,
                3600 // ttl in seconds
            );
        } else {
            $cached = true;
            $config = $configCache->get($cacheId);
        }

        $this->assertFalse($cached);

        if ($configCache->isExpired($cacheId)) {
            $cached = false;
            $configFactory = new ConfigFactory();
            $config = $configFactory->loadResources([
                FIXTURES_PATH.'/config/arrayfile1.php',
                FIXTURES_PATH.'/config/arrayfile2.php',
            ]);

            $configCache->set(
                $cacheId,
                $config,
                3600 // ttl in seconds
            );
        } else {
            $cached = true;
            $config = $configCache->get($cacheId);
        }

        $this->assertTrue($cached);
        $this->assertTrue($configCache->has($cacheId));
        $this->assertFalse($configCache->isExpired($cacheId));

        $configCache->delete($cacheId);
        $this->assertFalse($configCache->has($cacheId));
        $this->assertTrue($configCache->isExpired($cacheId));
    }

}