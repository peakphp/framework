<?php

declare(strict_types=1);

namespace Peak\Bedrock\Bootstrap;

use Peak\Bedrock\Bootstrap\Exception\InvalidBootableProcessException;
use Peak\Blueprint\Common\Bootable;
use Psr\Container\ContainerInterface;

class Bootstrap implements Bootable
{
    private array $processes;

    private BootableResolver $resolver;

    public function __construct(array $processes, ContainerInterface $container = null)
    {
        $this->processes = $processes;
        $this->resolver = new BootableResolver($container);
    }

    /**
     * @throws InvalidBootableProcessException
     */
    public function boot(): bool
    {
        foreach ($this->processes as $process) {
            $processResolved = $this->resolver->resolve($process);
            $processResolved->boot();
        }
        return true;
    }
}
