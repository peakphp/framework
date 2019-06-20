<?php

declare(strict_types=1);

namespace Peak\Di;

use Peak\Di\Exception\AmbiguousResolutionException;
use Peak\Di\Exception\InterfaceNotFoundException;

use function array_key_exists;
use function is_array;

class InterfaceResolver
{

    /**
     * @var BindingResolver
     */
    private $bindingResolver;

    /**
     * InterfaceResolver constructor.
     */
    public function __construct()
    {
        $this->bindingResolver = new BindingResolver();
    }

    /**
     * @param string $interface
     * @param Container $container
     * @param array $explicit
     * @return mixed|object
     * @throws AmbiguousResolutionException
     * @throws Exception\ClassDefinitionNotFoundException
     * @throws InterfaceNotFoundException
     * @throws \ReflectionException
     */
    public function resolve(string $interface, Container $container, $explicit = [])
    {
        // try to find a match in the container for a class or an interface
        if ($container->hasInterface($interface)) {
            $instance = $container->getInterface($interface);
            if (is_array($instance)) {
                if (empty($explicit) || !array_key_exists($interface, $explicit)) {
                    throw new AmbiguousResolutionException($interface, $instance);
                }
                return $container->get($explicit[$interface]);
            }
            return $container->get($instance);
        }

        // try to find a match in container definition
        if ($container->hasDefinition($interface)) {
            $definition = $container->getDefinition($interface);
            return $this->bindingResolver->resolve($definition, $container, [], $explicit);
        }

        throw new InterfaceNotFoundException($interface);
    }
}
