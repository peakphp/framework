<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

use Peak\Config\ConfigInterface;

class ConfigStream implements StreamInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * ConfigStream constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->config->toArray();
    }
}
