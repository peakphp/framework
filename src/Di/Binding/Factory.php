<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

use Peak\Di\Container;

use function is_null;

class Factory extends AbstractBinding
{
    /**
     * Constructor
     *
     * @param string $name
     * @param callable $definition
     */
    public function __construct($name, Callable $definition)
    {
        parent::__construct($name, self::FACTORY, $definition);
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

        return $definition($container, $args);
    }
}
