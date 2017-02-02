<?php

namespace Peak\Di;

use \ReflectionClass;

/**
 * Dependency Class Instanciator
 */
class ClassInstanciator
{
    /**
     * Instanciate a class
     * 
     * @param  string $class
     * @return object
     */
    public function instanciate($class, array $args = [])
    {
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs($args);
    }
    
}