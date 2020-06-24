<?php

namespace Peak\Bedrock\Bootstrap;

use Peak\Bedrock\Bootstrap\Exception\InvalidBootableProcessException;
use Peak\Blueprint\Common\Bootable;
use Peak\Blueprint\Common\ResourceResolver;
use Psr\Container\ContainerInterface;
use function class_exists;
use function is_callable;
use function is_string;

class BootableResolver implements ResourceResolver
{
    private ?ContainerInterface $container;

    public function __construct(?ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $item
     * @return Bootable
     * @throws InvalidBootableProcessException
     */
    public function resolve($item): Bootable
    {
        if(is_string($item) && class_exists($item)) {
            if (null !== $this->container) {
                $item = $this->container->get($item);
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
}
