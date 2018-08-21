<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Psr\Container\ContainerInterface;

/**
 * Class Kernel
 * @package Peak\Bedrock
 */
class Kernel implements KernelInterface
{
    /**
     * Peak version
     * @var string
     */
    const VERSION = '4.0';

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
     * Initialize the object
     */
    public function initialize()
    {
        // nothing buy default
    }
}