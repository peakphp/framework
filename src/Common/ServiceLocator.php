<?php

declare(strict_types=1);

namespace Peak\Common;

use Exception;

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
    public function __set(string $name, callable $closure): void
    {
        $this->register($name, $closure);
    }

    /**
     * shortcut for getService()
     *
     * @param string $name
     * @throws Exception
     */
    public function __get(string $name)
    {
        return $this->getService($name);
    }

    /**
     * Register a service closure
     *
     * @param  string   $name
     * @param  callable $closure
     * @return $this
     */
    public function register(string $name, callable $closure): ServiceLocator
    {
        $this->services[$name] = $closure;
        return $this;
    }

    /**
     * Get a service name closure
     *
     * @param  string $name
     * @return mixed  return the result of executed closure
     * @throws Exception
     */
    public function getService(string $name)
    {
        if (!array_key_exists($name, $this->services)) {
            throw new Exception('The service "'.$name.'" does not exists.');
        }

        return $this->services[$name]();
    }

    /**
     * List services
     *
     * @return array
     */
    public function listServices(): array
    {
        return array_keys($this->services);
    }

    /**
     * Check if has service name
     *
     * @param  $name
     * @return boolean
     */
    public function hasService(string $name): bool
    {
        return array_key_exists($name, $this->services);
    }
}
