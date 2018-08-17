<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Peak\Bedrock\Http\Request\HandlerResolver;
use Peak\Blueprint\Common\Resolvable;
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
     * @param Resolvable $handlerResolver
     * @param string|null $version
     * @return Application
     */
    public function create(
        string $environment,
        ContainerInterface $container,
        Resolvable $handlerResolver = null,
        string $version = '1.0'
    ) {

        $handlerResolver = $handlerResolver ?? new HandlerResolver($container);
        return new Application(
            new Kernel($environment, $container),
            $handlerResolver,
            $version
        );
    }
}