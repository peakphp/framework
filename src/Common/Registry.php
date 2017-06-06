<?php

namespace Peak\Common;

/**
 * Registry singleton container
 */
class Registry
{
    /**
     * Array of registered objects
     * @var array
     */
    protected static $objects = [];

    /**
     * Instance of registry
     * @var object
     */
    protected static $instance = null;

    /**
     * Clone
     */
    final private function __clone()
    {
        trigger_error('Can\'t clone registry', E_USER_ERROR);
    }

    /**
     * Make Registry not instantiable
     */
    final private function __construct()
    {
    }

    /**
     * Return an registered object or null
     *
     * @param  string $name
     * @return object/null
     */
    public function __get($name)
    {
        return isset(self::$objects[$name]) ? self::$objects[$name] : null;
    }

    /**
     * Set/Get registry instance
     *
     * @return object $instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Shortcut of method getInstance()
     *
     * @return object $instance
     */
    public static function o()
    {
        return self::getInstance();
    }

    /**
     * Same as method register but static
     *
     * @param  string $name
     * @param  object $obj
     * @return object
     */
    public static function set($name, $obj)
    {
        self::$objects[$name] = $obj;
        return $obj;
    }

    /**
     * Get registered object
     *
     * @param  string $name
     * @return string
     */
    public static function get($name)
    {
        if (self::isRegistered($name)) {
            return self::$objects[$name];
        }
    }

    /**
     * Get all registered object
     *
     * @return array
     */
    public static function getAll()
    {
        return self::$objects;
    }

    /**
     * Get class name of a registered object
     *
     * @param  string $name
     * @return string|false
     */
    public static function getClassName($name)
    {
        if (self::isRegistered($name)) {
            return get_class(self::$objects[$name]);
        }
        return false;
    }

    /**
     * Return objects list
     *
     * @return array
     */
    public static function getObjectsList()
    {
        return array_keys(self::$objects);
    }

    /**
     * Unregister an object
     *
     * @param string $name
     */
    public static function unregister($name)
    {
        if (self::isRegistered($name)) {
            unset(self::$objects[$name]);
        }
    }

    /**
     * Check if an object var name is registered
     *
     * @param string $name
     * @return bool
     */
    public static function isRegistered($name)
    {
        return isset(self::$objects[$name]) ? true : false;
    }

    /**
     * Check class name of registered object match
     *
     * @param string $name
     * @param string $class_name
     */
    public static function isInstanceOf($name, $class_name)
    {
        if (self::isRegistered($name)) {
            return (self::$objects[$name] instanceof $class_name) ? true : false;
        }
        return false;
    }
}
