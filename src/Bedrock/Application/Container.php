<?php

namespace Peak\Bedrock\Application;

use Peak\Di\ContainerInterface;

class Container
{
    protected static $container;

    public static function set(ContainerInterface $container)
    {
        self::$container = $container;
    }

    public static function get($instance = null)
    {
        if (!isset($instance)) {
            return self::$container;
        }

        return self::$container->getInstance($instance);
    }

    public static function instantiate($class, $args = [], $explict = [])
    {
        return self::$container->instantiate($class, $args, $explict);
    }
}