<?php

namespace Peak\Backpack\Application;

use Peak\Bedrock\Application\Application;
use Peak\Bedrock\Http\Request\HandlerResolver;
use Peak\Blueprint\Bedrock\Kernel;
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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

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
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
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
     * Build
     */
    public function build()
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

        $app = new Application(
            $kernel,
            $this->handlerResolver ?? new HandlerResolver($kernel->getContainer()),
            $this->handlerResolver ?? '1.0'
        );

        if (isset($this->name)) {
            $app->setName($this->name);
        }

        return $app;
    }




}