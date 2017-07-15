<?php

namespace Peak\Di;

use Peak\Common\Collection;
use \InvalidArgumentException;

/**
 * Dependencies Container
 */
class Container implements ContainerInterface
{
    /**
     * Container object instances collection
     * @var \Peak\Common\Collection
     */
    protected $instances;

    /**
     * Classes namespace alias
     * @var array
     */
    protected $aliases = [];

    /**
     * Container object interfaces collection
     * @var \Peak\Common\Collection
     */
    protected $interfaces;

    /**
     * Class instance creator
     * @var \Peak\Di\ClassInstantiator
     */
    protected $instantiator;

    /**
     * Container object instances collection
     * @var \Peak\Di\ClassResolver
     */
    protected $resolver;

    /**
     * Class definitions resolver
     * @var \Peak\Di\ClassDefinitions
     */
    protected $def_resolver;

    /**
     * Allow container to resolve automatically for your object needed
     * @var bool
     */
    protected $auto_wiring = true;

    /**
     * Container configuration definitions
     * @var array
     */
    protected $definitions = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->instances    = new Collection();
        $this->interfaces   = new Collection();
        $this->resolver     = new ClassResolver();
        $this->def_resolver = new ClassDefinitions();
        $this->instantiator = new ClassInstantiator();
    }

    /**
     * Instantiate a class
     *
     * The generated instance is not stored, but may use stored
     * instance(s) as dependency when needed
     *
     * @param  string $class     The class name to instantiate
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
    public function instantiate($class, $args = [], $explicit = [])
    {
        $resolver = $this->resolver;

        // if false, don't use reflection, use $definitions instead to resolve a class
        if (!$this->auto_wiring) {
            $resolver = $this->def_resolver;
        }

        // process class dependencies
        $args = $resolver->resolve($class, $this, $args, $explicit);

        // instantiate class with resolved dependencies and args if apply
        return $this->instantiator->instantiate($class, $args);
    }

    /**
     * Similar to instantiate(), it call a method on specified object
     *
     * @param  array  $callback The callable to be called
     * @param  array  $args     The parameters to be passed to the callback, as an indexed array
     * @param  array  $explicit @see instantiate
     * @return mixed  the method call return if any
     */
    public function call(array $callback, $args = [], $explicit = [])
    {
        // process class dependencies
        $args = $this->resolver->resolve($callback, $this, $args, $explicit);

        return call_user_func_array($callback, $args);
    }

    /**
     * Same as instantiate class but also store it with add
     *
     * @see instantiate() for params
     * @return object
     */
    public function instantiateAndStore($class, $args = [], $explicit = [])
    {
        $object = $this->instantiate($class, $args, $explicit);
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
     * @param  string|null $alias
     * @return $this
     */
    public function add($object, $alias = null)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException(__CLASS__.': add() first argument must be an object.');
        }

        $class = get_class($object);
        $this->instances[$class] = $object;

        if (isset($alias)) {
            $this->addAlias($alias, $class);
        }

        $interfaces = class_implements($object);
        if (is_array($interfaces)) {
            foreach ($interfaces as $i) {
                $this->addInterface($i, $class);
            }
        }

        return $this;
    }

    /**
     * Delete an instance if exists.
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
     * @param  string $name
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
     * @param string $name
     * @param string $class
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
     * @param  string $name
     * @return bool
     */
    public function hasInterface($name)
    {
        return isset($this->interfaces->$name);
    }

    /**
     * Get an interface
     *
     * @param   string $name
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
     * Add class definition
     *
     * @param $class
     * @param $definition
     * @return $this
     */
    public function addDefinition($class, $definition)
    {
        $this->definitions[$class] = $definition;
        return $this;
    }

    /**
     * Set definitions. Use definitions when autowiring is off
     *
     * @param  array $definitions
     * @return $this
     */
    public function setDefinitions(array $definitions)
    {
        $this->definitions = $definitions;
        return $this;
    }

    /**
     * Has a definition
     *
     * @param  string $name
     * @return bool
     */
    public function hasDefinition($name)
    {
        return isset($this->definitions[$name]);
    }

    /**
     * Get a definition
     *
     * @param   string $name
     * @return  array|string instance(s) matching definition name
     */
    public function getDefinition($name)
    {
        if ($this->hasDefinition($name)) {
            return $this->definitions[$name];
        }
        return null;
    }

    /**
     * Enable Auto Wiring
     *
     * @return $this
     */
    public function enableAutoWiring()
    {
        $this->auto_wiring = true;
        return $this;
    }

    /**
     * Disable Auto Wiring
     *
     * @return $this
     */
    public function disableAutoWiring()
    {
        $this->auto_wiring = false;
        return $this;
    }
}
