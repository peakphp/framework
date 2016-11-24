<?php
/**
 * Peak Objects Registry
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_Registry
{

	/**
	 * Array of registered objects
	 * @var array
	 */
    protected static $_objects = array();

    /**
     * Instance of registry
     * @var object
     */
    protected static $_instance = null;                               

    private final function __clone() { trigger_error('Can\'t clone registry', E_USER_ERROR); }
    private final function __construct() { }

    /**
	 * Return an registered object or null
	 *
	 * @param  string $name
	 * @return object/null
	 */
	public function __get($name) 
	{
	    return isset(self::$_objects[$name]) ? self::$_objects[$name] : null;
	}

    /**
     * set/get registry instance
     *
     * @return object $_instance
     */
    public static function getInstance()
	{
		if(is_null(self::$_instance)) self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Shortcut of method getInstance()
	 *
	 * @return object $_instance
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
	public static function set($name,$obj)
	{
	    self::$_objects[$name] = $obj;
	    return self::$_objects[$name];
	}

	/**
	 * Get registered object
	 *
	 * @param  string $name
	 * @return string
	 */
	public static function get($name)
	{
	    if(self::isRegistered($name)) return self::$_objects[$name];
	}

	/**
	 * Get class name of a registered object
	 *
	 * @param  string $name
	 * @return string|false
	 */
	public static function getClassName($name) 
	{
		if(self::isRegistered($name)) return get_class(self::$_objects[$name]);
		else return false;
	}

    /**
	 * Return objects list
	 *
	 * @return array
	 */
	public static function getObjectsList()
	{
	    return array_keys(self::$_objects);
	}

	/**
	 * Unregister an object
	 *
	 * @param string $name
	 */
	public static function unregister($name)
	{
	    if(self::isRegistered($name)) unset(self::$_objects[$name]);
	}

	/**
	 * Check if an object var name is registered
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function isRegistered($name)
	{
	    return isset(self::$_objects[$name]) ? true : false;
	}

	/**
	 * Check class name of registered object match
	 *
	 * @param string $name
	 * @param string $class_name
	 */
	public static function isInstanceOf($name, $class_name)
	{
	    if(self::isRegistered($name)) {
	        return (self::$_objects[$name] instanceof $class_name) ? true : false;
	    }
	    return false;
	}
}