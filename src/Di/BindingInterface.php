<?php

namespace Peak\Di;

use Psr\Container\ContainerInterface;

interface BindingInterface
{
    public function getName();

    public function getType();

    public function getDefinition();

    public function resolve(ContainerInterface $container, $args = [], $explicit = null);
}
