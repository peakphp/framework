<?php

namespace Peak\Di;

use Psr\Container\ContainerInterface;
use \Closure;

/**
 * Explicit Dependency declaration Resolver
 */
class ExplicitResolver
{

    /**
     * Resolve class arguments dependencies
     *
     * @param  string $class
     * @return object
     */
    public function resolve($needle, ContainerInterface $container, $explicit = null)
    {
        // Check for explicit dependency closure or object instance
        if (is_array($explicit) && array_key_exists($needle, $explicit)) {
            if ($explicit[$needle] instanceof Closure) {
                return $explicit[$needle]($container);
            } elseif (is_object($explicit[$needle])) {
                return $explicit[$needle];
            }
        } elseif($explicit instanceof Closure) {
            return $explicit($container);
        }
        return null;
    }
}
