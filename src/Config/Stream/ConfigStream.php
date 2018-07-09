<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

use Peak\Config\ConfigInterface;

/**
 * Class ConfigStream
 * @package Peak\Config\Stream
 */
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
