<?php

declare(strict_types=1);

namespace Peak\Collection;

use Exception;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Serializable;

/**
 * Class PropertiesBag
 * @package Peak\Collection
 */
class PropertiesBag implements ArrayAccess, Countable, IteratorAggregate, Serializable
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
     * @param $property
     * @return mixed
     * @throws Exception
     */
    public function __get($property)
    {
        if (!array_key_exists($property, $this->properties)) {
            throw new Exception('Property '.$property.' not found');
        }

        return $this->properties[$property];
    }

    /**
     * @param $key
     * @param $val
     */
    public function __set($property, $val)
    {
        $this->properties[$property] = $val;
    }

    /**
     * @param string $property
     * @return bool
     */
    public function __isset($property)
    {
        return array_key_exists($property, $this->properties);
    }

    /**
     * @param string $property
     */
    public function __unset($property)
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
    public function offsetExists($offset)
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
}
