<?php

declare(strict_types=1);

namespace Peak\Di;

use ReflectionClass;
use ReflectionException;

class ClassInstantiator
{
    /**
     * Instantiate a class
     *
     * @param string $class
     * @param array $args
     * @return object
     * @throws ReflectionException
     */
    public function instantiate(string $class, array $args = [])
    {
        if (empty($args)) {
            return new $class();
        }
        return (new ReflectionClass($class))->newInstanceArgs($args);
    }
}
