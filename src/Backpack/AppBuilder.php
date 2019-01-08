<?php

declare(strict_types=1);

namespace Peak\Backpack;

use Peak\Bedrock\Application\Application;
use Peak\Http\Request\HandlerResolver;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Di\Container;
use Psr\Container\ContainerInterface;
use \Closure;

/**
 * Class AppBuilder
 * @package Peak\Backpack\Application
 */
class AppBuilder
{
    /**
     * @var string
     */
    private $env;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var ResourceResolver
     */
    private $handlerResolver;

    /**
     * @var Dictionary
     */
    private $props;

    /**
     * Application class
     * @var string
     */
    private $class;

    /**
     * Kernel class
     * @var Kernel
     */
    private $kernelClass;

    /**
     * @var Closure
     */
    private $afterBuild;

    /**
     * @param string $env
     * @return $this
     */
    public function setEnv(string $env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @param Dictionary $props
     * @return $this
     */
    public function setProps(Dictionary $props)
    {
        $this->props = $props;
        return $this;
    }

    /**
     * @param Kernel $kernel
     * @return $this
     */
    public function setKernel(Kernel $kernel)
    {
        $this->kernel = $kernel;
        return $this;
    }

    /**
     * @param string $kernelClass
     * @return $this
     */
    public function setKernelClass(string $kernelClass)
    {
        $this->kernelClass = $kernelClass;
        return $this;
    }

    /**
     * @param ResourceResolver $handlerResolver
     * @return $this
     */
    public function setHandlerResolver(ResourceResolver $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
        return $this;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName(string $className)
    {
        $this->class = $className;
        return $this;
    }

    /**
     * @param Closure $fn
     * @return $this
     */
    public function executeAfterBuild(Closure $fn)
    {
        $this->afterBuild = $fn;
        return $this;
    }

    /**
     * @return Application
     */
    public function build()
    {
        return $this->internalBuild($this->class ?? Application::class);
    }

    /**
     * @param string|null $applicationClass
     * @return Application
     */
    private function internalBuild(string $applicationClass): \Peak\Blueprint\Bedrock\Application
    {
        $kernel = $this->kernel;
        if (!isset($kernel)) {
            $kernelClass = $this->kernelClass ?? \Peak\Bedrock\Kernel::class;
            $kernel = new $kernelClass(
                $this->env ?? 'prod',
                $this->container ?? new Container()
            );
        } elseif (isset($this->container) || isset($this->env) || isset($this->kernelClass)) {
            $this->triggerKernelError();
        }

        $app = new $applicationClass(
            $kernel,
            $this->handlerResolver ?? new HandlerResolver($kernel->getContainer()),
            $this->props ?? null
        );

        if (isset($this->afterBuild) && is_callable($this->afterBuild)) {
            ($this->afterBuild)($app);
        }

        return $app;
    }

    /**
     * Trigger an error with arguments container and env when a kernel has been set previously
     */
    private function triggerKernelError()
    {
        $msgErrorSuffix = 'setting will be ignored because Kernel had been set previously.';
        if (isset($this->container)) {
            trigger_error('Container '.$msgErrorSuffix);
        }
        if (isset($this->env)) {
            trigger_error('Env '.$msgErrorSuffix);
        }
        if (isset($this->kernelClass)) {
            trigger_error('Kernel class '.$msgErrorSuffix);
        }
    }
}
