<?php

declare(strict_types=1);

namespace Peak\Collection;

use Peak\Blueprint\Collection\Dictionary;
use JsonSerializable;
use Exception;
use ArrayIterator;

use function array_key_exists;
use function count;
use function json_encode;
use function serialize;
use function unserialize;

class PropertiesBag implements Dictionary, JsonSerializable
{
    /**
     * @var array
     */
    protected $properties = [];

    /**
     * Constructor.
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    /**
     * @param string $prop
     * @param null $default
     * @return mixed
     */
    public function get(string $prop, $default = null)
    {
        return $this->properties[$prop] ?? $default;
    }

    /**
     * @param string $prop
     * @param mixed $value
     * @return mixed
     */
    public function set(string $prop, $value)
    {
        $this->properties[$prop] = $value;
        return $this;
    }

    /**
     * @param string $prop
     * @return bool
     */
    public function has(string $prop): bool
    {
        return array_key_exists($prop, $this->properties);
    }

    /**
     * @param string $property
     * @return mixed
     * @throws Exception
     */
    public function &__get(string $property)
    {
        if (!array_key_exists($property, $this->properties)) {
            throw new Exception('Property '.$property.' not found');
        }

        return $this->properties[$property];
    }

    /**
     * @param string $property
     * @param mixed $val
     */
    public function __set(string $property, $val)
    {
        $this->properties[$property] = $val;
    }

    /**
     * @param string $property
     * @return bool
     */
    public function __isset(string $property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    /**
     * @param string $property
     */
    public function __unset(string $property)
    {
        unset($this->properties[$property]);
    }

    /**
     * Assign a value to the specified offset
     *
     * @param  string $offset
     * @param  mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        $this->properties[$offset] = $value;
    }

    /**
     * Whether an item exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->properties[$offset]);
    }

    /**
     * Item to delete
     *
     * @param  string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->properties[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->properties[$offset]) ? $this->properties[$offset] : null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->properties);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->properties);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->properties);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
        $this->properties = unserialize($data);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->properties;
    }
}
