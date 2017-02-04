<?php

namespace Peak\Di;

use Peak\Di\Container;

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
        // Try to find a match in the container for an interface
        if($container->hasInterface($interface)) {
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