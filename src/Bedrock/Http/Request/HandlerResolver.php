<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Request;

use Peak\Bedrock\Http\Middleware\CallableMiddleware;
use Peak\Bedrock\Http\StackInterface;
use Peak\Bedrock\Http\Request\Exception\HandlerNotFoundException;
use Peak\Bedrock\Http\Request\Exception\UnresolvableHandlerException;
use Peak\Di\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class HandlerResolver
 * @package Peak\Bedrock\Http\Request
 */
class HandlerResolver implements HandlerResolverInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Resolver constructor.
     *
     * @param ContainerInterface|null $container
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
        if ($handler instanceof StackInterface ||
            $handler instanceof MiddlewareInterface ||
            $handler instanceof RequestHandlerInterface) {
            return $handler;
        }

        if (is_callable($handler)) {
            return $this->resolveCallable($handler);
        }

        if (is_string($handler)) {
            return $this->resolveString($handler);
        }

        throw new UnresolvableHandlerException($handler);
    }

    /**
     * @param $handler
     * @return CallableMiddleware
     */
    protected function resolveCallable($handler)
    {
        // TODO add support of callable request handler too
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
        if (isset($this->container)) {
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
