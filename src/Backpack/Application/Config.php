<?php

namespace Peak\Backpack\Application;

use Peak\Blueprint\Common\ResourceLoader;
use Peak\Blueprint\Config\ConfigFactory;
use Peak\Config\Cache\FileCache;
use Peak\Config\ConfigCacheFactory;
use Psr\SimpleCache\CacheInterface;
use LogicException;

/**
 * Class Config
 * @package Peak\Backpack\Application
 */
class Config implements ResourceLoader
{
    /**
     * @var \Peak\Blueprint\Config\ConfigFactory
     */
    protected $configFactory = null;

    /**
     * @var string
     */
    protected $cachePath = null;

    /**
     * @var string
     */
    protected $cacheId = null;

    /**
     * @var integer
     */
    protected $cacheTtl = null;

    /**
     * @var CacheInterface
     */
    protected $cacheDriver = null;

    /**
     * @param \Peak\Blueprint\Config\ConfigFactory $configFactory
     * @return $this
     */
    public function setConfigFactory(ConfigFactory $configFactory)
    {
        if (isset($this->cachePath)) {
            throw new LogicException('You cannot specify config factory if you have set cache configuration');
        }
        $this->configFactory = $configFactory;
        return $this;
    }

    /**
     * @param string $cachePath
     * @param string $cacheId
     * @param $cacheTtl
     * @param CacheInterface|null $cacheDriver
     * @throws LogicException
     * @return $this
     */
    public function setCache(string $cachePath, string $cacheId, $cacheTtl, CacheInterface $cacheDriver = null)
    {
        if (isset($this->configFactory)) {
            throw new LogicException('You cannot specify cache configuration if you already have set a ConfigFactory instance');
        }
        $this->cachePath = $cachePath;
        $this->cacheId = $cacheId;
        $this->cacheTtl = $cacheTtl;
        $this->cacheDriver = $cacheDriver;
        return $this;
    }

    /**
     * @param mixed $resources
     * @return mixed|\Peak\Blueprint\Config\Config|\Peak\Config\Config
     * @throws {inherit}
     */
    public function load($resources)
    {
        return $this->loadConfig($resources);
    }

    /**
     * @param $resources
     * @param \Peak\Blueprint\Config\Config $config
     * @return \Peak\Blueprint\Config\Config|\Peak\Config\Config
     * @throws {inherit}
     */
    public function loadWith($resources, \Peak\Blueprint\Config\Config $config)
    {
        return $this->loadConfig($resources, $config);
    }

    /**
     * @param $resources
     * @param \Peak\Blueprint\Config\Config|null $config
     * @return \Peak\Blueprint\Config\Config|\Peak\Config\Config
     * @throws \Peak\Config\Exception\CachePathNotFoundException
     * @throws \Peak\Config\Exception\CachePathNotWritableException
     * @throws \Peak\Config\Exception\UnknownResourceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function loadConfig($resources, \Peak\Blueprint\Config\Config $config = null)
    {
        $configFactory = $this->configFactory;

        if (!isset($configFactory)) {
            if (!isset($this->cachePath)) {
                $configFactory = new \Peak\Config\ConfigFactory();
            } else {
                $cacheDriver = $this->cacheDriver;
                if (!isset($this->cacheDriver)) {
                    $cacheDriver = new FileCache($this->cachePath);
                }
                $configFactory = new ConfigCacheFactory(
                    $this->cacheId,
                    $this->cacheTtl,
                    new \Peak\Config\ConfigFactory(),
                    $cacheDriver
                );
            }
        }

        if (!isset($config)) {
            $config = new \Peak\Bedrock\Application\Config();
        }

        return $configFactory->loadResourcesWith($resources, $config);
    }
}