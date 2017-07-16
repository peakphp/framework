<?php

namespace Peak\Di;

use \Exception;

/**
 * Dependency Class Inspector
 */
class ClassDefinitions
{
    /**
     * @param $class
     * @param Container $container
     * @param array $args
     * @param callable $explicit
     */
    public function resolve($class, Container $container, $args = [], $explicit = null)
    {
        $definition = $container->getDefinition($class);

        if (!is_null($explicit) && !empty($explicit)) {
            $definition = $explicit;
        }

        if ($definition === null) {
            throw new Exception('Definition not found for '.$class);
        }

        $def_args = $definition;

        if (is_callable($definition)) {
            $def_args = $definition($container);
        }

        if (!is_array($def_args)) {
            throw new Exception('Definition for class must be an array or a closure returning an array');
        }

        // combine arguments
        $class_args = [];
        foreach ($def_args as $arg) {
            $class_args[] = $arg;
        }
        foreach ($args as $arg) {
            $class_args[] = $arg;
        }

        return $class_args;
    }
}
