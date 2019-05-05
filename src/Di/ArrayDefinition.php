<?php

declare(strict_types=1);

namespace Peak\Di;

use function array_shift;
use function class_exists;
use function is_array;
use function is_callable;
use function is_object;
use Peak\Di\Exception\InfiniteLoopResolutionException;

class ArrayDefinition
{
    /**
     * If true, check in the container before create a new instance of an object
     * @var bool
     */
    private $newInstanceOnly = false;

    /**
     * @var ClassInstantiator
     */
    private $instantiator;

    /**
     * @var ClassResolver
     */
    private $classResolver;

    /**
     * @var int
     */
    private $n = 0;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->instantiator = new ClassInstantiator();
        $this->classResolver = new ClassResolver();
    }

    /**
     * @param array $definition
     * @param Container $container
     * @param array $args
     * @return object
     * @throws Exception\AmbiguousResolutionException
     * @throws Exception\ClassDefinitionNotFoundException
     * @throws Exception\InterfaceNotFoundException
     * @throws InfiniteLoopResolutionException
     * @throws \ReflectionException
     */
    public function resolve(array $definition, Container $container, array $args = [])
    {
        $final_args = $definition;
        if (!empty($args)) { // add create argument at the end
            foreach ($args as $arg) {
                $final_args[] = $arg;
            }
        }
        $definition = array_shift($final_args);

        foreach ($final_args as $index => $arg) {
            if (is_array($arg)) {
                $final_args[$index] = $this->resolve($arg, $container);
            } elseif (is_callable($arg)) {
                $final_args[$index] = $arg($container);
            } elseif (is_object($arg)) {
                $final_args[$index] = $arg;
            } elseif (class_exists($arg) && $this->newInstanceOnly) {
                $this->n++;
                if ($this->n > 1) {
                    throw new InfiniteLoopResolutionException($definition);
                }
                $subArg = $this->classResolver->resolve($arg, $container, $args);
                $final_args[$index] = $this->instantiator->instantiate($arg, $subArg);
            } elseif (class_exists($arg) && !$this->newInstanceOnly) {
                if ($container->has($arg)) {
                    $final_args[$index] =  $container->get($arg);
                } elseif ($container->hasDefinition($arg)) {
                    $final_args[$index] = $container->resolve($arg);
                } else {
                    $this->n++;
                    if ($this->n > 1) {
                        throw new InfiniteLoopResolutionException($definition);
                    }
                    $subArg = $this->classResolver->resolve($arg, $container, $args);
                    $final_args[$index] = $this->instantiator->instantiate($arg, $subArg);
                }
            }
        }

        return $this->instantiator->instantiate($definition, $final_args);
    }

    /**
     * Set string resolution to new instance only
     *
     * @return $this
     */
    public function newInstancesOnly()
    {
        $this->newInstanceOnly = true;
        return $this;
    }
}
