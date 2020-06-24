<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

use Peak\Di\ArrayDefinition;
use Peak\Di\ClassInstantiator;
use Peak\Di\ClassResolver;
use Peak\Di\Container;
use Peak\Di\Exception\AmbiguousResolutionException;
use Peak\Di\Exception\ClassDefinitionNotFoundException;
use Peak\Di\Exception\InfiniteLoopResolutionException;
use Peak\Di\Exception\InterfaceNotFoundException;
use Peak\Di\Exception\InvalidDefinitionException;
use ReflectionException;
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

    private ClassInstantiator $instantiator;

    private ArrayDefinition $arrayDefinition;

    private ClassResolver $classResolver;

    private int $n = 0;

    /**
     * Constructor
     *
     * @param string $name
     * @param mixed $definition
     */
    public function __construct(string $name, $definition)
    {
        $this->instantiator = new ClassInstantiator();
        $this->arrayDefinition = new ArrayDefinition();
        $this->classResolver = new ClassResolver();
        parent::__construct($name, self::SINGLETON, $definition);
    }

    /**
     * @param Container $container
     * @param array $args
     * @param null $explicit
     * @return mixed|object|null
     * @throws InfiniteLoopResolutionException
     * @throws InvalidDefinitionException
     * @throws AmbiguousResolutionException
     * @throws ClassDefinitionNotFoundException
     * @throws InterfaceNotFoundException
     * @throws ReflectionException
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
            // a string should be resolved once only, if more than one, it mean that the class behind the
            // string have also dependency on itself and may go on infinite loop
            $this->n++;
            if ($this->n > 1) {
                throw new InfiniteLoopResolutionException($definition);
            }
            $args = $this->classResolver->resolve($definition, $container, $args, $explicit);
            $instance = $this->instantiator->instantiate($definition, $args);
        } elseif (is_array($definition)) {
            $instance = $this->arrayDefinition->resolve($definition, $container, $args);
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
