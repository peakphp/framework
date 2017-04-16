<?php

namespace Peak\Common;

use \Exception;

/**
 * Service Locator
 */
class ServiceLocator
{
    /**
     * Services array
     * @var array
     */
    protected $services = [];

    /**
     * shortcut for register()
     *
     * @param string   $name
     * @param callable $closure
     */
    public function __set($name, callable $closure)
    {
        $this->register($name, $closure);
    }

    /**
     * shortcut for getService()
     *
     * @param string   $name
     * @param callable $closure
     */
    public function __get($name)
    {
        return $this->getService($name);
    }

    /**
     * Register a service closure
     *
     * @param  string   $name
     * @param  callable $closure
     * @return $this    for chainning    
     */
    public function register($name, callable $closure)
    {
        $this->services[$name] = $closure;
        return $this;
    }

    /**
     * Get a service name closure
     *
     * @param  string $name
     * @return mixed  return the result of executed closure
     */
    public function getService($name)
    {
        if(!array_key_exists($name, $this->services)) {
            throw new Exception('The service "'.$name.'" does not exists.');
        }

        return $this->services[$name]();
    }

    /**
     * List services
     *
     * @return array
     */
    public function listServices()
    {
        return array_keys($this->services);
    }

    /**
     * Check if has service name
     *
     * @param  $name
     * @return boolean
     */
    public function hasService($name)
    {
        return array_key_exists($name, $this->services);
    }
}
