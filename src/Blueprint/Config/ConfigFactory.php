<?php

declare(strict_types=1);

namespace Peak\Blueprint\Config;

interface ConfigFactory
{
    /**
     * Load config with default Peak\Config
     *
     * @param array $resources
     * @return Config
     */
    public function loadResources(array $resources): Config;

    /**
     * Load config with custom Peak\Blueprint\Config\Config
     *
     * @param array $resources
     * @param Config $config
     * @return Config
     */
    public function loadResourcesWith(array $resources, Config $config): Config;
}
