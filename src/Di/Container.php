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
     * Classes namespace alias
     * @var array
     */
    protected $aliases = [];

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
     * Similar to instantiate(), it call a method on specified object
     * 
     * @param  array  $callback The callable to be called
     * @param  array  $args     The parameters to be passed to the callback, as an indexed array
     * @param  array  $explict  @see intantiates
     * @return mixed  the method call return if any
     */
    public function call(array $callback, $args = [], $explict = [])
    {
        // process class dependecies
        $args = $this->resolver->resolve($callback, $this, $args, $explict);

        return call_user_func_array($callback, $args);
    }

    /**
     * Same as instantiate class but also store it with add
     *
     * @see instantiate() for params
     * @return object
     */
    public function instantiateAndStore($class, $args = [], $explict = [])
    {
        $object = $this->instantiate($class, $args, $explict);
        $this->add($object);
        return $object;
    }

    /**
     * Has object instance
     *
     * @param  string $name
     */
    public function has($name)
    {
        return isset($this->instances->$name);
    }

    /**
     * Get an instance if exists, otherwise return null
     *
     * @param  string       $name
     * @return object|null
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->instances[$name];
        } elseif ($this->hasAlias($name) && $this->has($this->aliases[$name])) {
            return $this->instances[$this->aliases[$name]];
        }
        return null;
    }

    /**
     * Add an object instance. Chainable
     *
     * @param  string $object
     */
    public function add($object) 
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
    public function delete($name)
    {
        if ($this->has($name)) {

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
     * Add a class alias
     *
     * @param  string $name
     * @param  string $class
     * @return $this
     */
    public function addAlias($name, $class)
    {
        $this->aliases[$name] = $class;
        return $this;
    }

    /**
     * Has an alias
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasAlias($name)
    {
        return array_key_exists($name, $this->aliases);
    }

    /**
     * Add container itself
     * 
     * @return $this
     */
    public function addItself()
    {
        return $this->add($this);
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
     * Catalogue also class interface when using add
     *
     * @param strign $name
     * @param strign $class
     */
    protected function addInterface($name, $class)
    {
        if (!$this->hasInterface($name)) {
            $this->interfaces[$name] = $class;
        } else {
            $interface = $this->getInterface($name);
            if (is_array($interface)) {
                $interface[] = $class;
                $this->interfaces[$name] = $interface;
            } else {
                $this->interfaces[$name] = [
                    $this->interfaces[$name],
                    $class
                ];
            }
        }
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
}
