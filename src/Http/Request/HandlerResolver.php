<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use Peak\Http\Middleware\CallableMiddleware;
use Peak\Http\Request\Exception\HandlerNotFoundException;
use Peak\Http\Request\Exception\UnresolvableHandlerException;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Blueprint\Http\Stack;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HandlerResolver implements ResourceResolver
{
    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * HandlerResolver constructor.
     * @param ContainerInterface|null $container
     */
    public function __construct(?ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $handler
     * @return mixed|CallableMiddleware
     */
    public function resolve($handler)
    {
        if ($handler instanceof Stack ||
            $handler instanceof MiddlewareInterface ||
            $handler instanceof RequestHandlerInterface) {
            return $handler;
        }

        if (is_callable($handler)) {
            return $this->resolveCallable($handler);
        }

        if (is_string($handler)) {
            $handler = $this->resolveString($handler);
            if (is_callable($handler) && !$handler instanceof MiddlewareInterface) {
                return $this->resolveCallable($handler);
            }
            return $handler;
        }

        throw new UnresolvableHandlerException($handler);
    }

    /**
     * @param callable $handler
     * @return CallableMiddleware
     */
    protected function resolveCallable(callable $handler)
    {
        return new CallableMiddleware($handler);
    }

    /**
     * @param string $handler
     * @return mixed
     */
    protected function resolveString(string $handler)
    {
        if (!class_exists($handler)) {
            throw new HandlerNotFoundException($handler);
        }

        // resolve using a container
        if (null !== $this->container) {
            return $this->container->get($handler);
        }

        // manual instantiation, work only with empty constructor classes
        return new $handler();
    }
}
