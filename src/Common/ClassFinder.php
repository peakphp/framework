<?php

namespace Peak\Common;

class ClassFinder
{
    /**
     * Namespaces
     * @var array
     */
    protected $namespaces = [];

    protected $prefix;

    protected $suffix;

    /**
     * Constructor
     *
     * @param array $namespaces
     */
    public function __construct(array $namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * Add suffix to a class name
     *
     * @param $suffix
     * @return $this
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * Add prefix to a class name
     *
     * @param $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Return the first classname found or false
     *
     * @param  string $basename
     * @return string|null
     */
    public function findFirst($basename)
    {
        $basename = $this->getClassName($basename);
        foreach ($this->namespaces as $ns) {
            if (class_exists($ns.'\\'.$basename)) {
                return $ns.'\\'.$basename;
            }
        }
        return null;
    }

    /**
     * Return the last classname found or false
     *
     * @param  string $basename
     * @return string|null
     */
    public function findLast($basename)
    {
        $class = null;
        $basename = $this->getClassName($basename);
        foreach ($this->namespaces as $ns) {
            if (class_exists($ns.'\\'.$basename)) {
                $class = $ns.'\\'.$basename;
            }
        }
        return $class;
    }

    /**
     * Get class name
     *
     * @param $name
     * @return string
     */
    protected function getClassName($name)
    {
        return $this->prefix.$name.$this->suffix;
    }
}
