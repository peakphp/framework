<?php

declare(strict_types=1);

namespace Peak\Di;

use Psr\Container\ContainerInterface;

/**
 * Class BindingResolver
 * @package Peak\Di
 */
class BindingResolver
{
    /**
     * Resolve a binding request
     *
     * @param BindingInterface $binding
     * @param ContainerInterface $container
     * @param array $args
     * @param null $explicit
     * @return mixed
     */
    public function resolve(BindingInterface $binding, ContainerInterface $container, $args = [], $explicit = null)
    {
        return $binding->resolve($container, $args, $explicit);
    }
}
