<?php

namespace Peak\Common;

class DotNotationCollection extends Collection
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
                if (!array_key_exists($key, $array)) {
                    return $default;
                }
                $array = $array[$key];
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
                    if (!is_array($at)) {
                        throw new RuntimeException('Can not set value at this path ['.$path.'] because is not array.');
                    }
                    $at[array_shift($keys)] = $value;
                } else {
                    $key = array_shift($keys);
                    if (!isset($at[$key])) {
                        $at[$key] = [];
                    }
                    $at =& $at[$key];
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
        $this->set($path, $this->arrayMergeRecursiveDistinct($get, $values));
    }

    /**
     * Check if we have path
     *
     * @param  string $path
     * @return bool
     */
    public function has($path)
    {
        $keys = $this->explode($path);
        $array = $this->items;
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
            $array = $array[$key];
        }
        return true;
    }

    /**
     * Explode path string
     *
     * @param  string $path
     * @return array
     */
    protected function explode($path)
    {
        return preg_split(self::SEPARATOR, $path);
    }
}