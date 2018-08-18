<?php

declare(strict_types=1);

namespace Peak\Bedrock\Bootstrap;

use Peak\Blueprint\Common\Bootable;
use Psr\Container\ContainerInterface;

/**
 * Class Bootstrap
 * @package Peak\Bedrock\Bootstap
 */
class Bootstrap implements Bootable
{
    /**
     * @var array
     */
    private $processes;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var BootableResolver
     */
    private $resolver;

    /**
     * Bootstrap constructor.
     * @param array $processes
     * @param ContainerInterface|null $container
     */
    public function __construct(array $processes, ContainerInterface $container = null)
    {
        $this->processes = $processes;
        $this->container = $container;
        $this->resolver = new BootableResolver($container);
    }

    /**
     * Boot
     *
     * @return bool|mixed
     * @throws \Exception
     */
    public function boot()
    {
        foreach ($this->processes as $process) {
            $processResolved = $this->resolver->resolve($process);
            $processResolved->boot();

        }
        return true;
    }
}
