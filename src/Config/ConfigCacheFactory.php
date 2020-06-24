<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Blueprint\Config\ConfigFactory;
use Peak\Blueprint\Config\Config as ConfigBlueprint;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class ConfigCacheFactory implements ConfigFactory
{
    private string $cacheId;

    private int $ttl;

    private ConfigFactory $configFactory;

    private CacheInterface $configCache;

    /**
     * ConfigCacheFactory constructor.
     *
     * @param string $cacheId
     * @param int $ttl
     * @param ConfigFactory $configFactory
     * @param CacheInterface $configCache
     */
    public function __construct(
        string $cacheId,
        int $ttl,
        ConfigFactory $configFactory,
        CacheInterface $configCache
    ) {
        $this->cacheId = $cacheId;
        $this->ttl = $ttl;
        $this->configFactory = $configFactory;
        $this->configCache = $configCache;
    }

    /**
     * @param array $resources
     * @return ConfigBlueprint
     * @throws InvalidArgumentException
     */
    public function loadResources(array $resources): ConfigBlueprint
    {
        return $this->load($this->cacheId, $resources);
    }

    /**
     * @param array $resources
     * @param ConfigBlueprint $customConfig
     * @return ConfigBlueprint
     * @throws InvalidArgumentException
     */
    public function loadResourcesWith(array $resources, ConfigBlueprint $customConfig): ConfigBlueprint
    {
        return $this->load($this->cacheId, $resources, $customConfig);
    }

    /**
     * @param string $cacheId
     * @param array $resources
     * @param ConfigBlueprint|null $customConfig
     * @return ConfigBlueprint
     * @throws InvalidArgumentException
     */
    protected function load(string $cacheId, array $resources, ConfigBlueprint $customConfig = null): ConfigBlueprint
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

        $this->configCache->set($cacheId, $config, $this->ttl);

        return $config;
    }
}
