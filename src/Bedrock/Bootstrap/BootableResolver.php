<?php

namespace Peak\Bedrock\Bootstrap;

use Peak\Bedrock\Bootstrap\Exception\InvalidBootableProcessException;
use Peak\Blueprint\Common\Bootable;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Di\Container;
use Psr\Container\ContainerInterface;

class BootableResolver implements ResourceResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * BootableResolver constructor.
     * @param ContainerInterface|null $container
     */
    public function __construct(?ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Try to return a resolved item or throw an exception
     *
     * @param mixed $item
     * @return Bootable
     * @throws InvalidBootableProcessException
     * @throws \ReflectionException
     */
    public function resolve($item): Bootable
    {
        if(is_string($item) && class_exists($item)) {
            if (null !== $this->container) {
                $item = $this->resolveFromContainer($item);
            }
            if (is_string($item)) {
                $item = new $item();
            }
        } elseif (is_callable($item)) {
            $item = new CallableProcess($item);
        }

        if (!$item instanceof Bootable) {
            throw new InvalidBootableProcessException($item);
        }

        return $item;
    }

    /**
     * @param $item
     * @return mixed|object
     * @throws \Peak\Di\Exception\NoClassDefinitionException
     * @throws \ReflectionException
     */
    private function resolveFromContainer($item)
    {
        if ($this->container->has($item)) {
            return  $this->container->get($item);
        } elseif ($this->container instanceof Container) {
            return $this->container->create($item);
        }
        return $item;
    }
}
