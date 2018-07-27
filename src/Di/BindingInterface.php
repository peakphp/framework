<?php

declare(strict_types=1);

namespace Peak\Di;

use Psr\Container\ContainerInterface;

/**
 * Interface BindingInterface
 * @package Peak\Di
 */
interface BindingInterface
{
    public function getName();

    public function getType();

    public function getDefinition();

    /**
     * @param ContainerInterface $container
     * @param array $args
     * @param null $explicit
     * @return mixed
     */
    public function resolve(ContainerInterface $container, $args = [], $explicit = null);
}
