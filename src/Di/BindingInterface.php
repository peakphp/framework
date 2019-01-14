<?php

declare(strict_types=1);

namespace Peak\Di;

/**
 * Interface BindingInterface
 * @package Peak\Di
 */
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
