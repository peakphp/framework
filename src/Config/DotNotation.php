<?php

namespace Peak\Config;

use Peak\Collection;

/**
 * Dot notation for access multidimensional arrays.
 * 
 * $dn = new DotNotation([
 *     'bar'=> [
 *         'baz'=> ['foo' => true]
 *      ]
 * ]);
 * 
 * $value = $dn->get('bar.baz.foo');   // $value == true
 * $dn->set('bar.baz.foo', false);     // ['foo'=>false]
 * $dn->add('bar.baz', ['boo'=>true]); // ['foo'=>false,'boo'=>true]
 * 
 * @author Anton Medvedev <anton (at) elfet (dot) ru>
 * @version 2.0
 * @license MIT
 *
 * Adapted by Francois Lajoie for Peak
 */

class DotNotation extends Collection
{
    const SEPARATOR = '/[:\.]/';

    /**
     * Return a path value
     * 
     * @param string $path
     * @param string $default
     * @return mixed
     */
    public function get($path, $default = null)
    {
        $array = $this->items;

        if (!empty($path)) {
            $keys = $this->explode($path);
            foreach ($keys as $key) {
                if (isset($array[$key])) {
                    $array = $array[$key];
                } else {
                    return $default;
                }
            }
        }

        return $array;
    }

    /**
     * Add a path
     * 
     * @param string $path
     * @param mixed $value
     */
    public function set($path, $value)
    {
        if (!empty($path)) {
            $at = & $this->items;
            $keys = $this->explode($path);

            while (count($keys) > 0) {
                if (count($keys) === 1) {
                    if (is_array($at)) {
                        $at[array_shift($keys)] = $value;
                    } else {
                        throw new \RuntimeException("Can not set value at this path ($path) because is not array.");
                    }
                } else {
                    $key = array_shift($keys);

                    if (!isset($at[$key])) {
                        $at[$key] = array();
                    }

                    $at = & $at[$key];
                }
            }
        } else {
            $this->items = $value;
        }
    }

    /**
     * Merge a path with an array
     * 
     * @param string $path
     * @param array $values
     */
    public function add($path, array $values)
    {
        $get = (array)$this->get($path);
        $this->set($path, $this->_mergeRecursiveDistinct($get, $values));
    }

    /**
     * Check if we have path
     * 
     * @param string $path
     * @return bool
     */
    public function have($path)
    {
        $keys = $this->explode($path);
        $array = $this->items;
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $array = $array[$key];
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Explode path string
     * 
     * @param  string $path    
     */
    protected function explode($path)
    {
        return preg_split(self::SEPARATOR, $path);
    }
}
