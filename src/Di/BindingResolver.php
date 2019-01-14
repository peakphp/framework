<?php

declare(strict_types=1);

namespace Peak\Di;

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
     * @param Container $container
     * @param array $args
     * @param null $explicit
     * @return mixed
     */
    public function resolve(BindingInterface $binding, Container $container, $args = [], $explicit = null)
    {
        return $binding->resolve($container, $args, $explicit);
    }
}
