<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Psr\Container\ContainerInterface;

class Kernel implements \Peak\Blueprint\Bedrock\Kernel
{
    /**
     * Peak kernel version
     * @var string
     */
    const VERSION = '4.2.3';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $environment;

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
