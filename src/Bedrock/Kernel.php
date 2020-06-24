<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Psr\Container\ContainerInterface;

class Kernel implements \Peak\Blueprint\Bedrock\Kernel
{
    const VERSION = '5.0.0';

    private ContainerInterface $container;

    private string $environment;

    /**
     * Kernel constructor.
     *
     * @param string $environment
     * @param ContainerInterface $container
     */
    public function __construct(
        string $environment,
        ContainerInterface $container
    ) {
        $this->environment = $environment;
        $this->container = $container;
        $this->initialize();
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $this->environment;
    }

    /**
     * Initialize kernel
     */
    public function initialize()
    {
        // nothing by default
    }
}
