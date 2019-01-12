<?php

declare(strict_types=1);

namespace Peak\Di;

use Peak\Di\Exception\AmbiguousResolutionException;
use Peak\Di\Exception\InterfaceNotFoundException;
use Peak\Di\Exception\NotFoundException;

/**
 * Class InterfaceResolver
 * @package Peak\Di
 */
class InterfaceResolver
{
    /**
     * @param mixed $interface
     * @param Container $container
     * @param array $explicit
     * @return null|object
     * @throws AmbiguousResolutionException
     * @throws InterfaceNotFoundException
     * @throws NotFoundException
     */
    public function resolve($interface, Container $container, $explicit = [])
    {
        // Try to find a match in the container for a class or an interface
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
        throw new InterfaceNotFoundException($interface);
    }
}
