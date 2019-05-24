<?php

declare(strict_types=1);

namespace Peak\Blueprint\Backpack;

use Closure;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Common\ResourceResolver;
use Psr\Container\ContainerInterface;

interface AppBuilderBase
{
    /**
     * @param string $env
     */
    public function setEnv(string $env);

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @param mixed $props
     */
    public function setProps($props);

    /**
     * @param string $appClass
     */
    public function setAppClass(string $appClass);

    /**
     * @param Kernel $kernel
     */
    public function setKernel(Kernel $kernel);

    /**
     * @param string $kernelClass
     */
    public function setKernelClass(string $kernelClass);

    /**
     * @param Closure $fn
     */
    public function executeAfterBuild(Closure $fn);
}