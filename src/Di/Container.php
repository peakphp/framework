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

    protected $interfaces;

    protected $instantiator;

    protected $resolver;

    protected $iresolver;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->instances    = new Collection();
        $this->interfaces   = new Collection();
        $this->resolver     = new ClassResolver();
        $this->instantiator = new ClassInstantiator();
    }

    /**
     * Instantiate a class
     * 
     * The generated instance is not stored, but may use stored 
     * instance(s) as dependency when needed
     * 
     * @param  string $class     The class name to instanciate
     * @param  array  $args      Constructor argument(s) for parent and child if any
     * @param  array  $explicit  Determine which instance should be use for an interface name.
     *                           Required when you have multiple instances using the same interface name.
     *                           ex: ['myinterface' => 'myinstance3']
     * @return object
     */
    public function instantiate($class, $args = [], $explict = [])
    {
        // process class dependecies
        $args = $this->resolver->resolve($class, $this, $args, $explict);
 
        // instantiate class with resolved dependencies and args if apply
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
     * Has an interface
     */
    public function hasInterface($name)
    {
        return isset($this->interfaces->$name);
    }

    /**
     * Get an interface
     * 
     * @param   $name
     * @return  array|string instance(s) matching interface name
     */
    public function getInterface($name)
    {
        if($this->hasInterface($name)) {
            return $this->interfaces[$name];
        }
        return null;
    }

    /**
     * Add an object instance. Chainable
     * 
     * @param  string      $object
     * @param  string|null $alias if set, instance will be stored under an alias
     */
    public function addInstance($object) 
    {

        $class = get_class($object);
        $this->instances[$class] = $object;

        $interfaces = class_implements($object);
        if(is_array($interfaces)) {
            foreach($interfaces as $i) {
                $this->addInterface($i, $class);
            }
        }

        return $this;
    }

    /**
     * Catalogue also class interface when using addInstance
     * 
     * @param strign $name 
     * @param strign $class
     */
    protected function addInterface($name, $class)
    {
        if(!$this->hasInterface($name)) {
            $this->interfaces[$name] = $class;
        }
        else {
            $interface = $this->getInterface($name);
            if(is_array($interface)) {
                $interface[] = $class;
                $this->interfaces[$name] = $interface;
            }
            else {
                $this->interfaces[$name] = [
                    $this->interfaces[$name],
                    $class
                ];
            }
        }
    }
}