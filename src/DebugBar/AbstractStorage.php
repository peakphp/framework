<?php

namespace Peak\DebugBar;

use Peak\Common\Collection\Collection;

abstract class AbstractStorage extends Collection
{
    /**
     * AbstractStorage
     */
    public function __construct()
    {
        $this->load();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function get()
    {
        return $this->items;
    }

    /**
     * Load storage data
     */
    abstract protected function load();

    /**
     * Save stored data
     */
    abstract public function save();

    /**
     * Reset stored data
     */
    abstract public function reset();

    /**
     * Merge storage with an an array
     *
     * @param array $data
     * @return $this
     */
    public function mergeWith(array $data)
    {
        $this->items = array_merge($this->items, $data);
        return $this;
    }
}
