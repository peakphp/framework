<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

use Peak\Di\AbstractBinding;
use Peak\Di\ArrayDefinition;
use Peak\Di\ClassInstantiator;
use Psr\Container\ContainerInterface;

/**
 * Class Singleton
 * @package Peak\Di\Binding
 */
class Singleton extends AbstractBinding
{
    /**
     * Singleton status
     * @var bool
     */
    protected $instantiated = false;

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
    public function __construct($name, $definition)
    {
        $this->instantiator = new ClassInstantiator();
        parent::__construct($name, self::SINGLETON, $definition);
    }

    /**
     * Resolve the binding
     *
     * @param ContainerInterface $container
     * @param array $args
     * @param mixed $explicit
     * @return mixed|null
     * @throws \Exception
     */
    public function resolve(ContainerInterface $container, $args = [], $explicit = null)
    {
        $definition = $this->definition;

        if (!is_null($explicit) && !empty($explicit)) {
            $definition = $explicit;
            $is_explicit = true;
        } elseif (!isset($is_explicit) && $container->has($this->name)) {
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
            if (!isset($is_explicit)) {
                $container->set($instance);
            }
            return $instance;
        }

        throw new \Exception(__CLASS__.': Invalid definition for '.$this->name);
    }
}
