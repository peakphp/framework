<?php
namespace Peak;

use Countable;
use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;

/**
 * Simple collection object
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{

    /**
     * Collection items
     * @var array
     */
    protected $items = [];

    /**
     * Create a new collection
     */
    public function __construct($items = null)
    {
        if(is_array($items)) $this->items = $items;
    }

    /**
     * Create a new instance of collection
     * 
     * @param array $items 
     */
    public static function make($items)
    {
        return new static($items);
    }

    /**
     * Use most of php built in array_ functions with items
     * Don't work with passed by reference array like array_push
     *
     * ex: $obj->array_keys() or $obj->keys()
     * 
     * @param  string $func array_ func
     * @param  mixed  $argv 
     * @return mixed
     */
    public function __call($func, $argv)
    {
        if(is_callable('array_'.$func)) {
            return call_user_func('array_'.$func, $this->items, ...$argv);
        }
        if (!is_callable($func) || substr($func, 0, 6) !== 'array_') {
            throw new Exception('ERR_CUSTOM', __CLASS__.': method '.$func.' is unknown');
        }
        return call_user_func($func, $this->items, ...$argv);
    }

    /**
     * Get an item by key
     *
     * @param string $key
     */
    public function &__get ($key) 
    {
        return $this->items[$key];
    }

    /**
     * Assigns a value to the specified item
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key,$value) 
    {
        $this->items[$key] = $value;
    }

    /**
     * Whether or not an item exists by key
     *
     * @param   string $key
     * @return  bool
     */
    public function __isset ($key) 
    {
        return isset($this->items[$key]);
    }

    /**
     * Unsets an item by key
     * 
     * @param string $key
     */
    public function __unset($key) 
    {
        unset($this->items[$key]);
    }

    /**
     * Assign a value to the specified offset
     */
    public function offsetSet($offset, $value) 
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } 
        else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Whether an item exists
     *
     * @return bool
     */
    public function offsetExists($offset) 
    {
        return isset($this->items[$offset]);
    }

    /**
     * Item to delete
     */
    public function offsetUnset($offset) 
    {
        unset($this->items[$offset]);
    }

    /**
     * Offset to retrieve
     * 
     * @return mixed
     */
    public function offsetGet($offset) 
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * Count items
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Create iterator for $config
     *
     * @return iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Determine if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * To array
     */
    public function toArray()
    {
        return $this->items;
    }


    public function jsonSerialize()
    {
        return $this->items;
    }
}