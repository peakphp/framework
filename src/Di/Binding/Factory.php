<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

use Exception;
use Peak\Di\Container;

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
     * @throws Exception
     */
    public function resolve(Container $container, array $args = [], $explicit = null)
    {
        $definition = (!empty($explicit)) ? $explicit : $this->definition;
        return $definition($container, $args);
    }
}
