<?php

declare(strict_types=1);

namespace Peak\Backpack\Config;

use Peak\Blueprint\Common\ResourceLoader;
use Peak\Blueprint\Config\Config;
use Peak\Blueprint\Config\ConfigFactory;
use Peak\Config\Cache\FileCache;
use Peak\Config\ConfigCacheFactory;
use Peak\Config\Exception\CachePathNotFoundException;
use Peak\Config\Exception\CachePathNotWritableException;
use Peak\Config\Exception\UnknownResourceException;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use function trigger_error;

class ConfigLoader implements ResourceLoader
{

    protected ?ConfigFactory $configFactory = null;

    protected ?string $cachePath = null;

    protected ?string $cacheId = null;

    protected ?int $cacheTtl = null;

    protected ?CacheInterface $cacheDriver = null;

    public function setConfigFactory(ConfigFactory $configFactory): ConfigLoader
    {
        if (isset($this->cachePath)) {
            trigger_error('Cache configurations will be ignored because ConfigFactory have been set.');
        }
        $this->configFactory = $configFactory;
        return $this;
    }

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
     * @throws CachePathNotFoundException
     * @throws CachePathNotWritableException
     * @throws UnknownResourceException
     * @throws InvalidArgumentException
     */
    public function load($resources)
    {
        return $this->loadConfig($resources);
    }

    /**
     * @param array $resources
     * @param Config $config
     * @return Config|\Peak\Config\Config
     * @throws CachePathNotFoundException
     * @throws CachePathNotWritableException
     * @throws UnknownResourceException
     * @throws InvalidArgumentException
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
     * @throws CachePathNotFoundException
     * @throws CachePathNotWritableException
     * @throws UnknownResourceException
     * @throws InvalidArgumentException
     */
    private function loadConfig(array $resources, Config $config = null)
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
            $config = new \Peak\Config\Config();
        }

        return $configFactory->loadResourcesWith($resources, $config);
    }
}
