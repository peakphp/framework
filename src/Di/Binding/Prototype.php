<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

use Peak\Di\AbstractBinding;
use Peak\Di\ArrayDefinition;
use Peak\Di\ClassInstantiator;
use Peak\Di\ClassResolver;
use Peak\Di\Container;

use function is_array;
use function is_null;
use function is_string;
use Peak\Di\Exception\InfiniteLoopResolutionException;

class Prototype extends AbstractBinding
{
    /**
     * @var ClassInstantiator
     */
    private $instantiator;

    /**
     * @var ArrayDefinition
     */
    private $arrayDefinition;

    /**
     * @var ClassResolver
     */
    private $classResolver;

    /**
     * @var int
     */
    private $n = 0;

    /**
     * Constructor
     *
     * @param string $name
     * @param string|array $definition
     */
    public function __construct($name, $definition)
    {
        $this->instantiator = new ClassInstantiator();
        $this->arrayDefinition = new ArrayDefinition();
        $this->classResolver = new ClassResolver();
        parent::__construct($name, self::PROTOTYPE, $definition);
    }

    /**
     * Resolve the binding
     *
     * @param Container $container
     * @param array $args
     * @param callable|null $explicit
     * @return mixed|null
     * @throws \Exception
     */
    public function resolve(Container $container, array $args = [], $explicit = null)
    {
        $definition = $this->definition;

        if (!is_null($explicit) && !empty($explicit)) {
            $definition = $explicit;
        }

        if (is_string($definition)) {
            // a string should be resolved once only, if more than one, it mean that the class behind the
            // string have also dependency on itself and may go on infinite loop
            $this->n++;
            if ($this->n > 1) {
                throw new InfiniteLoopResolutionException($definition);
            }
            $args = $this->classResolver->resolve($definition, $container, $args, $explicit);
            $instance = $this->instantiator->instantiate($definition, $args);
            // since it is a prototype, when we reach this line, it mean that prototype has been resolved properly,
            // so we decrease $n to allow other call to this prototype
            $this->n--;
            return $instance;
        } elseif (is_array($definition)) {
            return $this->arrayDefinition
                ->newInstancesOnly()
                ->resolve($definition, $container, $args);
        }

        throw new \Exception(__CLASS__.': Invalid definition for '.$this->name);
    }
}
