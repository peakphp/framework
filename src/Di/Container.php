<?php

declare(strict_types=1);

namespace Peak\Di;

use Peak\Di\Binding\Factory;
use Peak\Di\Binding\Prototype;
use Peak\Di\Binding\Singleton;
use Peak\Di\Exception\ClassDefinitionNotFoundException;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_search;
use function call_user_func_array;
use function class_implements;
use function get_class;
use function is_array;

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
     * @param string $class
     * @param array $args
     * @param null $explicit
     * @return mixed|object
     * @throws ClassDefinitionNotFoundException
     * @throws Exception\AmbiguousResolutionException
     * @throws Exception\InterfaceNotFoundException
     * @throws \ReflectionException
     */
    public function create(string $class, $args = [], $explicit = null)
    {
        // check first for definition even if auto wiring is on
        $def = $this->getDefinition($class);
        if ($def === null && !$this->auto_wiring) {
            throw new ClassDefinitionNotFoundException($class);
        } elseif ($def !== null) {
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
     * @param array $callback
     * @param array $args
     * @param null $explicit
     * @return mixed
     * @throws ClassDefinitionNotFoundException
     * @throws Exception\AmbiguousResolutionException
     * @throws Exception\InterfaceNotFoundException
     * @throws \ReflectionException
     */
    public function call(array $callback, array $args = [], $explicit = null)
    {
        // process class dependencies
        $args = $this->resolver->resolve($callback, $this, $args, $explicit);

        return call_user_func_array($callback, $args);
    }

    /**
     * Same as create() but also store the created object before returning it
     *
     * @param string $class
     * @param array $args
     * @param null $explicit
     * @return mixed|object
     * @throws ClassDefinitionNotFoundException
     * @throws Exception\AmbiguousResolutionException
     * @throws Exception\InterfaceNotFoundException
     * @throws \ReflectionException
     */
    public function createAndStore(string $class, array $args = [], $explicit = null)
    {
        $object = $this->create($class, $args, $explicit);
        $this->set($object);
        return $object;
    }

    /**
     * Resolve a stored definition
     *
     * @param string $definition
     * @param array $args
     * @return mixed
     * @throws ClassDefinitionNotFoundException
     */
    public function resolve(string $definition, array $args = [])
    {
        $def = $this->getDefinition($definition);
        if ($def === null) {
            throw new ClassDefinitionNotFoundException($definition);
        }

        return $this->binding_resolver->resolve($def, $this, $args);
    }

    /**
     * Has object instance
     *
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
    }

    /**
     * Get an instance if exists, otherwise try to create it return null
     *
     * @param string $id
     * @return mixed|object
     * @throws ClassDefinitionNotFoundException
     * @throws Exception\AmbiguousResolutionException
     * @throws Exception\InterfaceNotFoundException
     * @throws \ReflectionException
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->instances[$id];
        } elseif ($this->hasAlias($id) && $this->has($this->aliases[$id])) {
            return $this->instances[$this->aliases[$id]];
        }

        return $this->create($id);
    }

    /**
     * Set an object instance. Chainable
     *
     * @param  object $object
     * @param  string|null $alias
     * @return Container
     */
    public function set(object $object, string $alias = null)
    {
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
     * @return Container
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

            //remove instance from singleton binding
            if ($this->hasDefinition($id)) {
                $definition = $this->getDefinition($id);
                if ($definition instanceof Singleton) {
                    $definition->deleteStoredInstance();
                }
            }
        }
        return $this;
    }

    /**
     * Add a class alias
     *
     * @param  string $name
     * @param  string $className
     * @return $this
     */
    public function addAlias(string $name, string $className)
    {
        $this->aliases[$name] = $className;
        return $this;
    }

    /**
     * Has an alias
     *
     * @param  string $name
     * @return boolean
     */
    public function hasAlias(string $name)
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
        return $this->set($this);
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
    protected function addInterface(string $name, string $class)
    {
        if (!$this->hasInterface($name)) {
            $this->interfaces[$name] = $class;
            return;
        }
        $interfaces = $this->getInterface($name);

        if (!is_array($interfaces)) {
            $interfaces = [$interfaces];
        }

        if (!in_array($class, $interfaces)) {
            $interfaces[] = $class;
            $this->interfaces[$name] = $interfaces;
        }
    }

    /**
     * Has an interface
     *
     * @param  string $name
     * @return bool
     */
    public function hasInterface(string $name)
    {
        return isset($this->interfaces[$name]);
    }

    /**
     * Get an interface
     *
     * @param string $name
     * @return mixed|null
     */
    public function getInterface(string $name)
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
     * Set class definition
     *
     * @param string $name
     * @param mixed $definition
     * @return $this
     */
    public function setDefinition(string $name, $definition)
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
    public function hasDefinition(string $name)
    {
        return isset($this->definitions[$name]);
    }

    /**
     * Get a definition
     *
     * @param   string $name
     * @return  mixed
     */
    public function getDefinition(string $name)
    {
        if ($this->hasDefinition($name)) {
            return $this->definitions[$name];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * Add a singleton definition
     *
     * @param string $name
     * @param mixed $definition
     * @return $this
     */
    public function bind(string $name, $definition)
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
    public function bindPrototype(string $name, $definition)
    {
        $this->definitions[$name] = new Prototype($name, $definition);
        return $this;
    }

    /**
     * Add a factory definition
     *
     * @param string $name
     * @param mixed $definition
     * @return $this
     */
    public function bindFactory(string $name, $definition)
    {
        $this->definitions[$name] = new Factory($name, $definition);
        return $this;
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
