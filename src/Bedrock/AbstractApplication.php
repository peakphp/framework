<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Peak\Bedrock\Bootstrap\Bootstrap;
use Peak\Blueprint\Bedrock\Application;
use Peak\Blueprint\Collection\Dictionary;
use Peak\Blueprint\Bedrock\Kernel;
use Psr\Container\ContainerInterface;

abstract class AbstractApplication implements Application
{
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var Dictionary|null
     */
    protected $props;

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
     * @throws \Exception
     */
    public function getProp(string $property, $default = null)
    {
        if (!isset($this->props)) {
            throw new \Exception('Application properties is not defined! Cannot use getProp()');
        }
        return $this->props->get($property, $default);
    }

    /**
     * @param string $property
     * @return bool
     * @throws \Exception
     */
    public function hasProp(string $property): bool
    {
        if (!isset($this->props)) {
            throw new \Exception('Application properties is not defined! Cannot use hasProp()');
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
     * @throws \Peak\Bedrock\Bootstrap\Exception\InvalidBootableProcessException
     * @throws \ReflectionException
     */
    public function bootstrap(array $processes)
    {
        $bootstrap = new Bootstrap($processes, $this->getContainer());
        $bootstrap->boot();
        return $this;
    }
}
