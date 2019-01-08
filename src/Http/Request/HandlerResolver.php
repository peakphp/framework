<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use Peak\Http\Middleware\CallableMiddleware;
use Peak\Http\Request\Exception\HandlerNotFoundException;
use Peak\Http\Request\Exception\UnresolvableHandlerException;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Blueprint\Http\Stack;
use Peak\Di\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class HandlerResolver
 * @package Peak\Http\Request
 */
class HandlerResolver implements ResourceResolver
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Resolver constructor.
     *
     * @param null|ContainerInterface $container
     */
    public function __construct(?ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $handler
     * @return mixed|object|CallableMiddleware|MiddlewareInterface|RequestHandlerInterface
     * @throws \ReflectionException
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
     * @param mixed $handler
     * @return CallableMiddleware
     */
    protected function resolveCallable($handler)
    {
        return new CallableMiddleware($handler);
    }

    /**
     * @param string $handler
     * @return mixed|object
     * @throws \ReflectionException
     */
    protected function resolveString(string $handler)
    {
        if (!class_exists($handler)) {
            throw new HandlerNotFoundException($handler);
        }

        // resolve using a container
        if (null !== $this->container) {
            if ($this->container->has($handler)) { // psr-11
                return $this->container->get($handler);
            } elseif ($this->container instanceof Container) {
                return $this->container->create($handler);
            }
        }

        // manual instantiation
        return new $handler();
    }
}
