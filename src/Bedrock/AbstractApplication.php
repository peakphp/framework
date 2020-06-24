<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Peak\Bedrock\Bootstrap\Bootstrap;
use Peak\Bedrock\Bootstrap\Exception\InvalidBootableProcessException;
use Peak\Blueprint\Bedrock\Application;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Psr\Container\ContainerInterface;

abstract class AbstractApplication implements Application
{
    protected Kernel $kernel;

    protected ?Dictionary $props;

    /**
     * @return Kernel
     */
    public function getKernel(): Kernel
    {
        return $this->kernel;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->kernel->getContainer();
    }

    /**
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public function getProp(string $property, $default = null)
    {
        if (!isset($this->props)) {
            return $default;
        }
        return $this->props->get($property, $default);
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasProp(string $property): bool
    {
        if (!isset($this->props)) {
            return false;
        }
        return $this->props->has($property);
    }

    /**
     * @return Dictionary|null
     */
    public function getProps(): ?Dictionary
    {
        return $this->props;
    }

    /**
     * Bootstrap bootable processes
     * @param array $processes
     * @return $this
     * @throws InvalidBootableProcessException
     */
    public function bootstrap(array $processes)
    {
        $bootstrap = new Bootstrap($processes, $this->getContainer());
        $bootstrap->boot();
        return $this;
    }
}
