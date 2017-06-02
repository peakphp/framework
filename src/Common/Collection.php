<?php

namespace Peak\Common;

use Countable;
use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;
use \Exception;
use \Closure;

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
     * Lock write
     * @var boolean
     */
    protected $read_only = false;

    /**
     * Create a new collection
     *
     * @param  array $items
     */
    public function __construct($items = null)
    {
        if (is_array($items)) {
            $this->items = $items;
        }
    }

    /**
     * Set read only on
     */
    public function readOnly()
    {
        $this->read_only = true;
    }

    /**
     * Check if its read only
     *
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->read_only;
    }

    /**
     * Create a new instance of collection
     *
     * @param  array $items
     * @return Collection
     */
    public static function make($items = null)
    {
        return new static($items);
    }

    /**
     * Use most of php built in array_ functions that accept an array as first arguments
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
        if (is_callable('array_'.$func)) {
            return call_user_func('array_'.$func, $this->items, ...$argv);
        }
        if (!is_callable($func) || substr($func, 0, 6) !== 'array_') {
            throw new Exception(__CLASS__.': method '.$func.' is unknown');
        }
        return call_user_func($func, $this->items, ...$argv);
    }

    /**
     * Get an item by key
     *
     * @param  string $key
     * @return mixed
     */
    public function &__get($key)
    {
        return $this->items[$key];
    }

    /**
     * Assigns a value to the specified item
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        if (!$this->isReadOnly()) {
            $this->items[$key] = $value;
        }
    }

    /**
     * Whether or not an item exists by key
     *
     * @param   string $key
     * @return  bool
     */
    public function __isset($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * Unset an item by key
     *
     * @param string $key
     */
    public function __unset($key)
    {
        if (!$this->isReadOnly()) {
            unset($this->items[$key]);
        }
    }

    /**
     * Assign a value to the specified offset
     *
     * @param  string $offset
     * @param  mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        if ($this->isReadOnly()) {
            return;
        }

        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Whether an item exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * Item to delete
     *
     * @param  string $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->isReadOnly()) {
            return;
        }
        unset($this->items[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param  string $offset
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
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
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
     * Empty the collection
     */
    public function strip()
    {
        $this->items = [];
    }

    /**
     * To array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * To simple object
     */
    public function toObject()
    {
        return (object)$this->items;
    }

    /**
     * Json serialize
     *
     * @param  integer $options Bitmask (see php.net json_encode)
     * @param  integer $depth   Set the maximum depth. Must be greater than zero.
     * @return string
     */
    public function jsonSerialize($options = 0, $depth = 512)
    {
        return json_encode($this->items, $options, $depth);
    }

    /**
     * Array map
     *
     * @param  Closure $closure
     */
    public function map(Closure $closure)
    {
        $this->items = array_map($closure, $this->items);
    }

    /**
     * Merge two arrays recursively overwriting the keys in the first array
     * if such key already exists
     *
     * @param  array $a      Array to merge to the current collection
     * @param  array|null $b If specified, $b will be merge into $a and replace current collection
     */
    public function mergeRecursiveDistinct($a, $b = null, $get_result = false)
    {
        if (!isset($b)) {
            $this->items = $this->_mergeRecursiveDistinct($this->items, $a);
        } else {
            if (!$get_result) {
                $this->items = $this->_mergeRecursiveDistinct($a, $b);
            } else {
                return $this->_mergeRecursiveDistinct($a, $b);
            }
        }
    }

    /**
     * Internal process for mergeRecursiveDistinct()
     *
     * @param  array $a
     * @param  array $b
     * @return array
     */
    protected function _mergeRecursiveDistinct($a, $b)
    {
        // merge arrays if both variables are arrays
        if (is_array($a) && is_array($b)) {
            // loop through each right array's entry and merge it into $a
            foreach ($b as $key => $value) {
                if (isset($a[$key])) {
                    $a[$key] = $this->_mergeRecursiveDistinct($a[$key], $value);
                } else {
                    if ($key === 0) {
                        $a = [0 => $this->_mergeRecursiveDistinct($a, $value)];
                    } else {
                        $a[$key] = $value;
                    }
                }
            }
        } else {
            $a = $b; // one of values is not an array
        }

        return $a;
    }
}
