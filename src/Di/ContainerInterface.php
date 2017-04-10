<?php

namespace Peak\Di;

/**
 * Container interface
 */
interface ContainerInterface
{
    /**
     * Has object instance
     *
     * @param  string $name
     */
    public function has($name);

    /**
     * Get an instance if exists, otherwise return null
     *
     * @param  string       $name
     * @return object|null
     */
    public function get($name);
}
