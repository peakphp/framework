<?php
namespace Peak\Config;

use IteratorAggregate;
use Countable;

/**
 * Variables registry for configs stuff
 */
class Base implements IteratorAggregate, Countable
{
    /**
     * Contains all variables
     * @var array
     */
    protected $_vars = [];

    /**
     * Set array of data OR a file optionnaly
     *
     * @param array $vars
     */
    public function __construct($vars = null)
    {
        if(is_array($vars)) $this->setVars($vars);
    }

    /**
     * Set a new variable
     *
     * @param string $name
     * @param misc   $val
     */
    public function __set($name,$val)
    {
        $this->_vars[$name] = $val;
    }

    /**
     * Get a variable
     *
     * @param  string $name
     * @return misc   Will return null if variable keyname is not found
     */
    public function &__get($name)
    {
        if(array_key_exists($name, $this->_vars)) return $this->_vars[$name];
        else return ${null};
    }

    /**
     * Isset variable
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        return (array_key_exists($name, $this->_vars)) ? true : false;
    }

    /**
     * Unset variable
     *
     * @param string $name
     */
    public function __unset($name)
    {
        if(array_key_exists($name, $this->_vars)) unset($this->_vars[$name]);
    }

    /**
     * Create iterator for $config
     *
     * @return iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_vars);
    }

    /**
     * Implement Countable func
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_vars);
    }

    /**
     * Get all variables
     *
     * @return array
     */
    public function getVars()
    {
        return $this->_vars;
    }

    /**
     * Set an array into object
     *
     * @param array $array
     */
    public function setVars($array)
    {
        $this->_vars = $array;
    }

    /**
     * Flush all variable stored
     */
    public function flushVars()
    {
        $this->_vars = array();
    }

    public function merge($data)
    {
        $this->setVars(
            self::arrayMergeRecursive($this->_vars, $data)
        );
    }

    /**
     * Merge two arrays recursively overwriting the keys in the first array
     * if such key already exists
     *
     * @param  mixed $a Left array to merge right array into
     * @param  mixed $b Right array to merge over the left array
     * @return mixed
     */
    static function arrayMergeRecursive($a, $b)
    {
        // merge arrays if both variables are arrays
        if (is_array($a) && is_array($b)) {
            // loop through each right array's entry and merge it into $a
            foreach ($b as $key => $value) {
                if (isset($a[$key])) {
                    $a[$key] = self::arrayMergeRecursive($a[$key], $value);
                } else {
                    if($key === 0) $a= array(0 => self::arrayMergeRecursive($a, $value));
                    else $a[$key] = $value;
                }
            }
        } 
        else $a = $b; // one of values is not an array

        return $a;
    }
}