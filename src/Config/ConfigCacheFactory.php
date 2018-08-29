<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Blueprint\Config\Config as ConfigBlueprint;
use Peak\Config\Exception\UnknownResourceException;
use Psr\SimpleCache\CacheInterface;

/**
 * Class ConfigCacheFactory
 * @package Peak\Config
 */
class ConfigCacheFactory
{
    /**
     * @var ConfigFactory
     */
    private $configFactory;

    /**
     * @var CacheInterface
     */
    private $configCache;

    /**
     * CacheFactoryFactory constructor.
     * @param ConfigFactory $configFactory
     * @param CacheInterface $configCache
     */
    public function __construct(ConfigFactory $configFactory, CacheInterface $configCache)
    {
        $this->configFactory = $configFactory;
        $this->configCache = $configCache;
    }

    /**
     * @param string $cacheId
     * @param int $ttl
     * @param array $resources
     * @return ConfigBlueprint
     * @throws UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function loadResources(string $cacheId, int $ttl, array $resources): ConfigBlueprint
    {
        return $this->load($cacheId, $ttl, $resources);
    }

    /**
     * @param string $cacheId
     * @param int $ttl
     * @param array $resources
     * @param ConfigBlueprint $customConfig
     * @return ConfigBlueprint
     * @throws UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function loadResourcesWith(string $cacheId, int $ttl, array $resources, ConfigBlueprint $customConfig): ConfigBlueprint
    {
        return $this->load($cacheId, $ttl, $resources, $customConfig);
    }

    /**
     * @param $cacheId
     * @param $ttl
     * @param $resources
     * @param ConfigBlueprint|null $customConfig
     * @return ConfigBlueprint
     * @throws UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function load(string $cacheId, int $ttl, array $resources, ConfigBlueprint $customConfig = null): ConfigBlueprint
    {
        $config = $this->configCache->get($cacheId);
        if (null !== $config) {
            return $config;
        }

        if (isset($customConfig)) {
            $config = $this->configFactory->loadResourcesWith($resources, $customConfig);
        } else {
            $config = $this->configFactory->loadResources($resources);
        }

        $this->configCache->set($cacheId, $config, $ttl);

        return $config;
    }
}
