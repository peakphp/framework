<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Blueprint\Config\Config as ConfigBlueprint;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Config\Exception\UnknownResourceException;

class ConfigFactory implements \Peak\Blueprint\Config\ConfigFactory
{
    /**
     * @var FilesHandlers|null
     */
    protected $filesHandlers;

    /**
     * @var ResourceResolver|null
     */
    private $configResolver;

    /**
     * ConfigFactory constructor.
     * @param ResourceResolver|null $configResolver
     */
    public function __construct(ResourceResolver $configResolver = null)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * @param FilesHandlers|null $filesHandlers
     * @return $this
     */
    public function setFilesHandlers(?FilesHandlers $filesHandlers)
    {
        $this->filesHandlers = $filesHandlers;
        return $this;
    }

    /**
     * @param array $resources
     * @return ConfigBlueprint
     * @throws UnknownResourceException
     */
    public function loadResources(array $resources): ConfigBlueprint
    {
        return $this->processResources($resources, new Config());
    }

    /**
     * @param array $resources
     * @param ConfigBlueprint $customConfig
     * @return ConfigBlueprint
     * @throws UnknownResourceException
     */
    public function loadResourcesWith(array $resources, ConfigBlueprint $customConfig): ConfigBlueprint
    {
        return $this->processResources($resources, $customConfig);
    }

    /**
     * @param array $resources
     * @param ConfigBlueprint $config
     * @return ConfigBlueprint
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
            // @todo fix method not in signature
            $config->mergeRecursiveDistinct($stream->get());
        }
        return $config;
    }
}
