<?php

namespace Peak\Di;

use Peak\Common\Collection;
use Peak\Di\ClassResolver;
use Peak\Di\ClassInstantiator;
use Peak\Di\ContainerInterface;

/**
 * Dependencies Container
 */
class Container implements ContainerInterface
{
    /**
     * Container object instances collection
     * @var Peak\Common\Collection
     */
    protected $instances;

    /**
     * Container object interfaces collection
     * @var Peak\Common\Collection
     */
    protected $interfaces;

    /**
     * Class instantiator
     * @var Peak\Di\ClassInstantiator
     */
    protected $instantiator;

    /**
     * Container object instances collection
     * @var Peak\Di\ClassResolver
     */
    protected $resolver;

    /**
     * Constructor
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
     *                           Required when you have multiple stored instances using the same interface name.
     *                           ex: ['myinterface' => 'myinstance3']
     *                           This support also custom closure
     *                           ex: ['myinterface' => function() { 
     *                                   return new MyClass(); // myclass implement myinterface
     *                               }]
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
        if ($this->hasInstance($name)) {
            return $this->instances[$name];
        }
        return null;
    }

    /**
     * Get all stored instances
     *
     * @return object
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * Has an interface
     *
     * @return bool
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
        if ($this->hasInterface($name)) {
            return $this->interfaces[$name];
        }
        return null;
    }

    /**
     * Get all stored interfaces
     *
     * @return object
     */
    public function getInterfaces()
    {
        return $this->interfaces;
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
        if (is_array($interfaces)) {
            foreach ($interfaces as $i) {
                $this->addInterface($i, $class);
            }
        }

        return $this;
    }

    /**
     * Delete an instance if exists. Chainable.
     *
     * @param  $name 
     * @return $this      
     */
    public function deleteInstance($name)
    {
        if ($this->hasInstance($name)) {

            //remove instance
            unset($this->instances[$name]);

            //remove interface reference if exists
            foreach ($this->interfaces as $int => $classes) {

                $key = array_search($name, $classes);
                if ($key !== false) {
                    unset($classes[$key]);
                    $this->interfaces->$int = $classes;
                }
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
        if (!$this->hasInterface($name)) {
            $this->interfaces[$name] = $class;
        }
        else {
            $interface = $this->getInterface($name);
            if (is_array($interface)) {
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
