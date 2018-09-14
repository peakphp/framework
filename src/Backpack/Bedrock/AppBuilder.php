<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock;

use Peak\Bedrock\Application\Application;
use Peak\Bedrock\Http\Request\HandlerResolver;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Di\Container;
use Psr\Container\ContainerInterface;

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
     * @param string $environment
     */
    public function setEnv(string $env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * @param ContainerInterface $container
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
     */
    public function setKernel(Kernel $kernel)
    {
        $this->kernel = $kernel;
        return $this;
    }

    /**
     * @param ResourceResolver $handlerResolver
     */
    public function setHandlerResolver(ResourceResolver $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
        return $this;
    }

    /**
     * @return Application
     */
    public function build()
    {
        return $this->internalBuild();
    }

    /**
     * @param string $applicationClass
     * @return Application
     */
    public function buildWith(string $applicationClass)
    {
        return $this->internalBuild($applicationClass);
    }

    /**
     * @param string|null $applicationClass
     * @return Application
     */
    private function internalBuild(string $applicationClass = null): \Peak\Blueprint\Http\Application
    {
        $kernel = $this->kernel;
        if (!isset($kernel)) {
            $kernel = new \Peak\Bedrock\Kernel(
                $this->env ?? 'prod',
                $this->container ?? new Container()
            );
        } elseif (isset($this->container) || isset($this->environement)) {
            $msgErrorSuffix = 'setting will be ignored because Kernel have been set.';
            if (isset($this->container)) {
                trigger_error('Container '.$msgErrorSuffix);
            }
            if (isset($this->env)) {
                trigger_error('Env '.$msgErrorSuffix);
            }
        }

        $appClassName = Application::class;
        if ($applicationClass) {
            $appClassName  = $applicationClass;
        }

        $app = new $appClassName(
            $kernel,
            $this->handlerResolver ?? new HandlerResolver($kernel->getContainer()),
            $this->props ?? null
        );

        return $app;
    }



}