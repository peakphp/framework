<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

use Peak\Di\AbstractBinding;
use Peak\Di\ArrayDefinition;
use Peak\Di\ClassInstantiator;
use Peak\Di\Container;
use Peak\Di\Exception\InvalidDefinitionException;

use function is_array;
use function is_callable;
use function is_null;
use function is_object;
use function is_string;

class Singleton extends AbstractBinding
{
    /**
     * @var mixed
     */
    private $storedInstance = null;

    /**
     * @var ClassInstantiator
     */
    private $instantiator;

    /**
     * Constructor
     *
     * @param string $name
     * @param mixed $definition
     */
    public function __construct(string $name, $definition)
    {
        $this->instantiator = new ClassInstantiator();
        parent::__construct($name, self::SINGLETON, $definition);
    }

    /**
     * Resolve the binding
     *
     * @param Container $container
     * @param array $args
     * @param null $explicit
     * @return mixed|object|null
     * @throws InvalidDefinitionException
     * @throws \Peak\Di\Exception\ClassDefinitionNotFoundException
     * @throws \ReflectionException
     */
    public function resolve(Container $container, array $args = [], $explicit = null)
    {
        $definition = $this->definition;

        if (null !== $this->storedInstance) {
            $definition = $this->storedInstance;
        }

        $is_explicit = false;
        if (!is_null($explicit) && !empty($explicit)) {
            $definition = $explicit;
            $is_explicit = true;
        }

        if (!$is_explicit && $container->has($this->name)) {
            return $container->get($this->name);
        }

        if (is_callable($definition)) {
            $instance = $definition($container, $args);
        } elseif (is_object($definition)) {
            $instance = $definition;
        } elseif(is_string($definition)) {
            $instance = $this->instantiator->instantiate($definition, $args);
        } elseif (is_array($definition)) {
            $instance = (new ArrayDefinition())->resolve($definition, $container, $args);
        }

        if (isset($instance)) {
            if (!$is_explicit) {
                $this->storedInstance = $instance;
                $container->set($instance);
            }
            return $instance;
        }

        throw new InvalidDefinitionException($this->name);
    }

    /**
     * Removed stored instance
     */
    public function deleteStoredInstance()
    {
        $this->storedInstance = null;
    }
}
