<?php

declare(strict_types=1);

namespace Peak\Backpack;

use Peak\Blueprint\Common\ResourceLoader;
use Peak\Blueprint\Config\Config;
use Peak\Blueprint\Config\ConfigFactory;
use Peak\Config\Cache\FileCache;
use Peak\Config\ConfigCacheFactory;
use Psr\SimpleCache\CacheInterface;

/**
 * Class ConfigLoader
 * @package Peak\Backpack
 */
class ConfigLoader implements ResourceLoader
{
    /**
     * @var ConfigFactory|null
     */
    protected $configFactory = null;

    /**
     * @var string|null
     */
    protected $cachePath = null;

    /**
     * @var string|null
     */
    protected $cacheId = null;

    /**
     * @var integer|null
     */
    protected $cacheTtl = null;

    /**
     * @var CacheInterface|null
     */
    protected $cacheDriver = null;

    /**
     * @param ConfigFactory $configFactory
     * @return $this
     */
    public function setConfigFactory(ConfigFactory $configFactory)
    {
        if (isset($this->cachePath)) {
            trigger_error('Cache configurations will be ignored because ConfigFactory have been set.');
        }
        $this->configFactory = $configFactory;
        return $this;
    }

    /**
     * @param string $path
     * @param string $uid
     * @param int $ttlInSec
     * @param CacheInterface|null $driver
     * @return $this
     */
    public function setCache(string $path, string $uid, int $ttlInSec, CacheInterface $driver = null)
    {
        if (isset($this->configFactory)) {
            trigger_error('Cache configurations will be ignored because ConfigFactory have been set.');
        }
        $this->cachePath = $path;
        $this->cacheId = $uid;
        $this->cacheTtl = $ttlInSec;
        $this->cacheDriver = $driver;
        return $this;
    }

    /**
     * @param mixed $resources
     * @return mixed|Config|\Peak\Config\Config
     * @throws \Peak\Config\Exception\CachePathNotFoundException
     * @throws \Peak\Config\Exception\CachePathNotWritableException
     * @throws \Peak\Config\Exception\UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function load($resources)
    {
        return $this->loadConfig($resources);
    }

    /**
     * @param array $resources
     * @param Config $config
     * @return Config|\Peak\Config\Config
     * @throws \Peak\Config\Exception\CachePathNotFoundException
     * @throws \Peak\Config\Exception\CachePathNotWritableException
     * @throws \Peak\Config\Exception\UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function loadWith(array $resources, Config $config)
    {
        // todo to remove
        return $this->loadConfig($resources, $config);
    }

    /**
     * @param array $resources
     * @param Config|null $config
     * @return Config
     * @throws \Peak\Config\Exception\CachePathNotFoundException
     * @throws \Peak\Config\Exception\CachePathNotWritableException
     * @throws \Peak\Config\Exception\UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function loadConfig(array $resources, \Peak\Blueprint\Config\Config $config = null)
    {
        $configFactory = $this->configFactory;

        if (!isset($configFactory)) {
            if (!isset($this->cachePath)) {
                $configFactory = new \Peak\Config\ConfigFactory();
            } else {
                $configFactory = new ConfigCacheFactory(
                    (string) $this->cacheId,
                    (int) $this->cacheTtl,
                    new \Peak\Config\ConfigFactory(),
                    $this->cacheDriver ?? new FileCache($this->cachePath)
                );
            }
        }

        if (!isset($config)) {
            $config = new \Peak\Bedrock\Application\Config();
        }

        return $configFactory->loadResourcesWith($resources, $config);
    }
}
