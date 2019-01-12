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
    /**
     * @param ContainerInterface $container
     * @param array $args
     * @param mixed $explicit
     * @return mixed
     */
    public function resolve(ContainerInterface $container, array $args = [], $explicit = null);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getType();

    /**
     * @return mixed
     */
    public function getDefinition();


}
