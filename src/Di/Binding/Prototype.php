<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

use Peak\Di\AbstractBinding;
use Peak\Di\ArrayDefinition;
use Peak\Di\ClassInstantiator;
use Peak\Di\Container;

use function is_array;
use function is_null;
use function is_string;

class Prototype extends AbstractBinding
{
    /**
     * @var ClassInstantiator
     */
    private $instantiator;

    /**
     * Constructor
     *
     * @param string $name
     * @param string|array $definition
     */
    public function __construct($name, $definition)
    {
        $this->instantiator = new ClassInstantiator();
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

        $def_args = [];

        if (is_string($definition)) {
            return $this->instantiator->instantiate($definition, $args);
        } elseif (is_array($definition)) {
            return (new ArrayDefinition())
                ->newInstancesOnly()
                ->resolve($definition, $container, $args);
        }

        throw new \Exception(__CLASS__.': Invalid definition for '.$this->name);
    }
}
