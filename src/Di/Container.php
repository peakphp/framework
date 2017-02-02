<?php

namespace Peak\Di;

use Peak\Collection;
use Peak\Di\ClassResolver;
use Peak\Di\ClassInstantiator;

/**
 * Dependecies Container
 */
class Container
{
    protected $instances;

    protected $instantiator;

    protected $resolver;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->instances    = new Collection();
        $this->resolver     = new ClassResolver();
        $this->instantiator = new ClassInstantiator();
    }

    /**
     * Instantiate a class
     * 
     * @param  string $class The class name to instanciate
     * @param  array  $args  Constructor argument(s) if any
     * @return object
     */
    public function instantiate($class, $args = [])
    {
        $args = $this->resolver->resolve($class, $this, $args);
        return $this->instantiator->instantiate($class, $args);
    }

    /**
     * Has object instance
     * 
     * @param  string $name
     */
    public function hasInstance($name)
    {
        return isset($this->instances->$name);
    }

    /**
     * Get an instance if exists, otherwise return null
     * 
     * @param  string       $name
     * @return object|null
     */
    public function getInstance($name)
    {
        if($this->hasInstance($name)) {
            return $this->instances[$name];
        }
        return null;
    }

    /**
     * Add an object instance. Chainable
     * 
     * @param  string $object
     */
    public function addInstance($object) 
    {
        $class = get_class($object);
        $this->instances[$class] = $object;
        return $this;
    }
}