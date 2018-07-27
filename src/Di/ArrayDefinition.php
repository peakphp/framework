<?php

declare(strict_types=1);

namespace Peak\Di;

use Psr\Container\ContainerInterface;

/**
 * Class ArrayDefinition
 * @package Peak\Di
 */
class ArrayDefinition
{
    /**
     * If true, check in the container before create a new instance of an object
     * @var bool
     */
    protected $new_instances_only = false;

    /**
     * @var ClassInstantiator
     */
    private $instantiator;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->instantiator = new ClassInstantiator();
    }

    /**
     * Resolve an array definition recursively
     *
     * @param array $definition
     * @param ContainerInterface $container
     * @param array $args
     * @return object
     */
    public function resolve(array $definition, ContainerInterface $container, array $args = [])
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
            } elseif (is_object($arg)) {
                $final_args[$index] = $arg;
            } elseif (is_callable($index)) {
                $final_args[$index] = $arg($container);
            } elseif (class_exists($arg) && $this->new_instances_only) {
                $final_args[$index] = $this->instantiator->instantiate($arg);
            } elseif (class_exists($arg) && !$this->new_instances_only) {
                if ($container->has($arg)) {
                    $final_args[$index] =  $container->get($arg);
                } elseif ($container->hasDefinition($arg)) {
                    $final_args[$index] = $container->resolve($arg);
                } else {
                    $final_args[$index] = $this->instantiator->instantiate($arg);
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
        $this->new_instances_only = true;
        return $this;
    }
}
