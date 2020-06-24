<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

use Peak\Di\Container;

interface BindingInterface
{
    /**
     * @param Container $container
     * @param array $args
     * @param mixed $explicit
     * @return mixed
     */
    public function resolve(Container $container, array $args = [], $explicit = null);

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getType(): int;

    /**
     * @return mixed
     */
    public function getDefinition();


}
