<?php

declare(strict_types=1);

namespace Peak\Di;

use \ReflectionClass;

/**
 * Class ClassInstantiator
 * @package Peak\Di
 */
class ClassInstantiator
{
    /**
     * Instantiate a class
     *
     * @param $class
     * @param array $args
     * @return object
     * @throws \ReflectionException
     */
    public function instantiate($class, array $args = [])
    {
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs($args);
    }
}
