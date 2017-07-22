<?php

namespace Peak\Di;

use Peak\Di\Binding\Factory;
use Peak\Di\Binding\Instance;
use Peak\Di\Binding\Prototype;
use Peak\Di\Binding\Singleton;
use Psr\Container\ContainerInterface;
use \Closure;
use \InvalidArgumentException;

/**
 * Dependencies Container
 */
class Container implements ContainerInterface
{
    /**
     * Container object instances collection
     * @var array
     */
    protected $instances = [];

    /**
     * Classes namespace alias
     * @var array
     */
    protected $aliases = [];

    /**
     * Container object interfaces collection
     * @var array
     */
    protected $interfaces = [];

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
     * @var BindingResolver
     */
    protected $binding_resolver;

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
        $this->instantiator = new ClassInstantiator();
        $this->resolver = new ClassResolver();
        $this->binding_resolver = new BindingResolver();
    }

    /**
     * Instantiate a class
     *
     * The generated instance is not stored, but may use stored
     * instance(s) as dependency when needed
     *
     * @param  string $class     The class name to instantiate
     * @param  array  $args      Constructor argument(s) for parent and child if any
     * @param  mixed  $explicit  Determine which instance should be use for an interface name.
     *                           Required when you have multiple stored instances using the same interface name.
     *                           ex: ['myinterface' => 'myinstance3']
     *                           This support also custom closure
     *                           ex: ['myinterface' => function() {
     *                                   return new MyClass(); // myclass implement myinterface
     *                               }]
     * @return object
     */
    public function create($class, $args = [], $explicit = null)
    {
        // if false, don't use reflection, use $definitions binding instead to resolve a class
        if (!$this->auto_wiring) {
            $def = $this->getDefinition($class);
            if (is_null($def)) {
                throw new \Exception(__CLASS__.': no definition found for '.$class);
            }
            return $this->binding_resolver->resolve(
                $this->getDefinition($class),
                $this,
                $args,
                $explicit
            );
        }

        // process class dependencies with reflection
        $args = $this->resolver->resolve($class, $this, $args, $explicit);

        // instantiate class with resolved dependencies and args if apply
        return $this->instantiator->instantiate($class, $args);
    }

    /**
     * Similar to instantiate(), it call a method on specified object
     *
     * @param  array  $callback The callable to be called
     * @param  array  $args     The parameters to be passed to the callback, as an indexed array
     * @param  mixed  $explicit @see instantiate
     * @return mixed  the method call return if any
     */
    public function call(array $callback, $args = [], $explicit = null)
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
    public function createAndStore($class, $args = [], $explicit = [])
    {
        $object = $this->create($class, $args, $explicit);
        $this->add($object);
        return $object;
    }

    /**
     * Resolve a stored definition
     *
     * @param string $definition
     * @param array $args
     * @throws \Exception
     */
    public function resolve($definition, $args = [])
    {
        $def = $this->getDefinition($definition);
        if (is_null($def)) {
            throw new \Exception('No definition found for '.$definition);
        }

        return $this->binding_resolver->resolve($def, $this, $args);
    }

    /**
     * Has object instance
     *
     * @param  string $id
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
    }

    /**
     * Get an instance if exists, otherwise return null
     *
     * @param  string $id
     * @return object|null
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->instances[$id];
        } elseif ($this->hasAlias($id) && $this->has($this->aliases[$id])) {
            return $this->instances[$this->aliases[$id]];
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
     * @param  string $id
     * @return $this
     */
    public function delete($id)
    {
        if ($this->has($id)) {
            //remove instance
            unset($this->instances[$id]);

            //remove interface reference if exists
            foreach ($this->interfaces as $int => $classes) {
                $key = array_search($id, $classes);
                if ($key !== false) {
                    unset($classes[$key]);
                    $this->interfaces[$int] = $classes;
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
        return isset($this->aliases[$name]);
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
     * @return array
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
        return isset($this->interfaces[$name]);
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
     * @return array
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * Add class definition
     *
     * @param string $name
     * @param Closure $definition
     * @return $this
     */
    public function addDefinition($name, $definition)
    {
        $this->definitions[$name] = $definition;
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
     * Add a singleton definition
     *
     * @param string $name
     * @param mixed $definition
     * @return $this
     */
    public function bind($name, $definition)
    {
        $this->definitions[$name] = new Singleton($name, $definition);
        return $this;
    }

    /**
     * Add a prototype definition
     *
     * @param string $name
     * @param mixed $definition
     * @return $this
     */
    public function bindPrototype($name, $definition)
    {
        $this->definitions[$name] = new Prototype($name, $definition);
        return $this;
    }

    /**
     * Add a factory definition
     *
     * @param $name
     * @param $definition
     * @return $this
     */
    public function bindFactory($name, $definition)
    {
        $this->definitions[$name] = new Factory($name, $definition);
        return $this;
    }

    /**
     * Enable Auto Wiring
     *
     * @return $this
     */
    public function enableAutowiring()
    {
        $this->auto_wiring = true;
        return $this;
    }

    /**
     * Disable Auto Wiring
     *
     * @return $this
     */
    public function disableAutowiring()
    {
        $this->auto_wiring = false;
        return $this;
    }
}
