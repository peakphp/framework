<?php

namespace Peak\Common;

class ClassFinder
{
    /**
     * Namespaces
     * @var array
     */
    protected $namespaces = [];

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
     * Return the first classname found or false
     *
     * @param  string $basename
     * @return string|false
     */
    public function findFirst($basename)
    {
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
     * @return string|false
     */
    public function findLast($basename)
    {
        $class = null;
        foreach ($this->namespaces as $ns) {
            if (class_exists($ns.'\\'.$basename)) {
                $class = $ns.'\\'.$basename;
            }
        }
        return $class;
    }
}
