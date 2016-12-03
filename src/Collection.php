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
    protected $_items = [];

    /**
     * Create a new collection
     */
    public function __construct($items = null)
    {
        $this->_items = $items;
    }

    /**
     * Use most of php built in array_ functions with items
     * Don't work with passed by reference array like array_push
     *
     * ex: $obj->array_keys()
     * 
     * @param  string $func array_ func
     * @param  mixed  $argv 
     * @return mixed
     */
    public function __call($func, $argv)
    {
        if (!is_callable($func) || substr($func, 0, 6) !== 'array_') {
            throw new Exception('ERR_CUSTOM', __CLASS__.': method '.$func.' is unknown');
        }
        return call_user_func($func, $this->_items, ...$argv);
    }

    /**
     * Get an item by key
     *
     * @param string $key
     */
    public function &__get ($key) 
    {
        return $this->_items[$key];
    }

    /**
     * Assigns a value to the specified item
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key,$value) 
    {
        $this->_items[$key] = $value;
    }

    /**
     * Whether or not an item exists by key
     *
     * @param   string $key
     * @return  bool
     */
    public function __isset ($key) 
    {
        return isset($this->_items[$key]);
    }

    /**
     * Unsets an item by key
     * 
     * @param string $key
     */
    public function __unset($key) 
    {
        unset($this->_items[$key]);
    }

    /**
     * Assign a value to the specified offset
     */
    public function offsetSet($offset, $value) 
    {
        if (is_null($offset)) {
            $this->_items[] = $value;
        } 
        else {
            $this->_items[$offset] = $value;
        }
    }

    /**
     * Whether an item exists
     *
     * @return bool
     */
    public function offsetExists($offset) 
    {
        return isset($this->_items[$offset]);
    }

    /**
     * Item to delete
     */
    public function offsetUnset($offset) 
    {
        unset($this->_items[$offset]);
    }

    /**
     * Offset to retrieve
     * 
     * @return mixed
     */
    public function offsetGet($offset) 
    {
        return isset($this->_items[$offset]) ? $this->_items[$offset] : null;
    }

    /**
     * Count items
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->_items);
    }

    /**
     * Create iterator for $config
     *
     * @return iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_items);
    }


    public function jsonSerialize()
    {
        return $this->_items;
    }
}