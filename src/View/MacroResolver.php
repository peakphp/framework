<?php

namespace Peak\View;

use Peak\Blueprint\Common\ResourceResolver;
use \Closure;
use Peak\Di\Container;
use Peak\View\Exception\InvalidMacroException;
use Psr\Container\ContainerInterface;

class MacroResolver implements ResourceResolver
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * MacroResolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(?ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $macro
     * @return Closure|mixed|object
     * @throws InvalidMacroException
     * @throws \ReflectionException
     */
    public function resolve($macro)
    {
        if (is_callable($macro)) {
            $closure = Closure::fromCallable($macro);
        } elseif (is_string($macro)) {
            $closure = $this->resolverString($macro);
        }

        if (!$closure instanceof Closure) {
            throw new InvalidMacroException($closure);
        }

        return $closure;
    }

    /**
     * @param $macro
     * @return mixed|object
     * @throws \ReflectionException
     */
    public function resolverString($macro)
    {
        $macroClosure = $macro;

        // resolve using a container
        if (null !== $this->container) {
            if ($this->container->has($macro)) { // psr-11
                $macroClosure = $this->container->get($macro);
            } elseif ($this->container instanceof \Peak\Di\Container) {
                $macroClosure = $this->container->create($macro);
            }
        } elseif (class_exists($macro)) {
            $macro = new $macro();
        }

        if (is_object($macroClosure) && is_callable($macroClosure)) {
            return Closure::fromCallable($macroClosure);
        }

        return $macroClosure;
    }
}