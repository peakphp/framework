<?php

namespace Peak\Di;

use \ReflectionClass;

/**
 * Dependency Class Instantiator
 */
class ClassInstantiator
{
    /**
     * Instanciate a class
     * 
     * @param  string $class
     * @return object
     */
    public function instantiate($class, array $args = [])
    {
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs($args);
    }
    
}