<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Blueprint\Config\Config as ConfigBlueprint;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Config\Exception\UnknownResourceException;

/**
 * Class ConfigFactory
 * @package Peak\Config
 */
class ConfigFactory implements \Peak\Blueprint\Config\ConfigFactory
{
    /**
     * @var FilesHandlers
     */
    protected $filesHandlers;

    /**
     * @var ResourceResolver
     */
    private $configResolver;

    /**
     * ConfigFactory constructor.
     *
     * @param FilesHandlers $filesHandlers
     */
    public function __construct(ResourceResolver $configResolver = null)
    {
        $this->resolver = $configResolver;
    }


    /**
     * @param FilesHandlers $filesHandlers
     * @return $this
     */
    public function setFilesHandlers(?FilesHandlers $filesHandlers)
    {
        $this->filesHandlers = $filesHandlers;
        return $this;
    }

    /**
     * @param array $resources
     * @return Config
     * @throws UnknownResourceException
     */
    public function loadResources(array $resources): ConfigBlueprint
    {
        return $this->processResources($resources, new Config());
    }

    /**
     * @param array $resources
     * @param Config $customConfig
     * @return Config
     * @throws UnknownResourceException
     */
    public function loadResourcesWith(array $resources, ConfigBlueprint $customConfig): ConfigBlueprint
    {
        return $this->processResources($resources, $customConfig);
    }

    /**
     * @param array $resources
     * @param Config $config
     * @return Config
     * @throws UnknownResourceException
     */
    protected function processResources(array $resources, ConfigBlueprint $config): ConfigBlueprint
    {
        $filesHandler = $this->filesHandlers;
        if (!isset($filesHandler)) {
            $filesHandler = new FilesHandlers(null);
        }

        $configResolver = $this->configResolver;
        if (!isset($configResolver)) {
            $configResolver = new ConfigResolver($filesHandler);
        }

        foreach ($resources as $resource) {
            $stream = $configResolver->resolve($resource);
            $config->mergeRecursiveDistinct($stream->get());
        }
        return $config;
    }
}
