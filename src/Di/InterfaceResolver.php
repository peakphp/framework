<?php

namespace Peak\Di;

use Peak\Di\Container;

use Closure;

/**
 * Dependency Interface Resolver
 */
class InterfaceResolver
{
    /**
     * Resolve class arguments dependencies
     * 
     * @param  string $class
     * @return object
     */
    public function resolve($interface, Container $container, $explicit = [])
    {
        // Check for explicit dependency declaration closure
        if(array_key_exists($interface, $explicit) && $explicit[$interface] instanceof Closure) {
            return $explicit[$interface]();
        }
        // Try to find a match in the container for an interface
        else if($container->hasInterface($interface)) {
            $instance = $container->getInterface($interface);
            if(is_array($instance)) {
                if(empty($explicit) || !array_key_exists($interface, $explicit)) {
                    throw new \LogicException ('Dependecies for interface '.$interface.' is ambiguous. There is '.count($instance).' differents instances for this interface.');
                }
                return $container->getInstance($explicit[$interface]);
            }
            else {
                return $container->getInstance($instance);
            }
        }
        else {
            throw new \Exception('Could not find an instance that implement interface '.$interface);
        }
    } 
}