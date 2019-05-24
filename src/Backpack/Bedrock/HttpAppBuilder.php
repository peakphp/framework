<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock;

use Peak\Bedrock\Http\Application;
use Peak\Blueprint\Bedrock\HttpApplication;
use Peak\Http\Request\HandlerResolver;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Di\Container;
use \Exception;

use function is_callable;
use function trigger_error;

class HttpAppBuilder extends AbstractAppBuilder implements \Peak\Blueprint\Backpack\HttpAppBuilder
{
    /**
     * @var ResourceResolver|null
     */
    private $handlerResolver;

    /**
     * @var bool
     */
    private $addToContainerAfterBuild = false;

    /**
     * @var string|null
     */
    private $aliasContainer = null;

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
     * @param string|null $alias
     * @return $this
     */
    public function addToContainerAfterBuild(string $alias = null)
    {
        $this->addToContainerAfterBuild = true;
        $this->aliasContainer = $alias;
        return $this;
    }

    /**
     * @return HttpApplication
     * @throws Exception
     */
    public function build(): HttpApplication
    {
        return $this->internalBuild($this->appClass ?? Application::class);
    }

    /**
     * @param string $applicationClass
     * @return \Peak\Blueprint\Bedrock\HttpApplication
     * @throws \Exception
     */
    private function internalBuild(string $applicationClass): \Peak\Blueprint\Bedrock\HttpApplication
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

        if ($this->addToContainerAfterBuild) {
            $this->addToContainer($app);
        }

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

    /**
     * @param \Peak\Blueprint\Bedrock\HttpApplication $app
     * @throws \Exception
     */
    private function addToContainer(\Peak\Blueprint\Bedrock\HttpApplication $app)
    {
        $container = $app->getKernel()->getContainer();
        if (!$container instanceof Container) {
            throw new Exception('Cannot add application instance to the container');
        }
        $container->set($app);
    }
}
