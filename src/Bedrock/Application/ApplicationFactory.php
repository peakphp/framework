<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Http\Request\HandlerResolver;
use Peak\Bedrock\Kernel;
use Peak\Blueprint\Bedrock\Kernel as KernelInterface;
use Peak\Blueprint\Common\ResourceResolver;
use Psr\Container\ContainerInterface;

/**
 * Class AppFactory
 * @package Peak\Bedrock
 */
class ApplicationFactory
{
    /**
     * @param string $environment
     * @param ContainerInterface $container
     * @param ResourceResolver|null $handlerResolver
     * @param string $version
     * @return Application
     */
    public function create(
        string $environment,
        ContainerInterface $container,
        ResourceResolver $handlerResolver = null,
        string $version = '1.0'
    ) {
        $handlerResolver = $handlerResolver ?? new HandlerResolver($container);
        return new Application(
            new Kernel($environment, $container),
            $handlerResolver,
            $version
        );
    }

    /**
     * @param KernelInterface $kernel
     * @param ResourceResolver|null $handlerResolver
     * @param string $version
     * @return Application
     */
    public function createFromKernel(
        KernelInterface $kernel,
        ResourceResolver $handlerResolver = null,
        string $version = '1.0'
    ) {
        $handlerResolver = $handlerResolver ?? new HandlerResolver($kernel->getContainer());
        return new Application($kernel, $handlerResolver, $version);
    }
}