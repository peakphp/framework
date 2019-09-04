<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use Peak\Blueprint\Common\Arrayable;

class RouteArgs implements \ArrayAccess, Arrayable
{
    /**
     * @var array
     */
    private $args;

    /**
     * RouteArgs constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
    }

    /**
     * @deprecated
     * @return array
     */
    public function raw()
    {
        return $this->args;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->args;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->args[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        return isset($this->args[$name]);
    }

    /**
     * Offset to retrieve
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->args[$offset]) ? $this->args[$offset] : null;
    }

    /**
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->args[$offset]);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->args[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->args[$offset]);
    }
}
