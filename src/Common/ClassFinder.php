<?php

declare(strict_types=1);

namespace Peak\Common;

use function class_exists;

class ClassFinder
{
    /**
     * Namespaces
     * @var array
     */
    protected $namespaces = [];

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
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
     * @param string $suffix
     * @return $this
     */
    public function setSuffix(string $suffix): ClassFinder
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * Add prefix to a class name
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix): ClassFinder
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Return the first class name found or false
     *
     * @param  string $basename
     * @return string|null
     */
    public function findFirst(string $basename): ?string
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
     * Return the last class name found or false
     *
     * @param  string $basename
     * @return string|null
     */
    public function findLast(string $basename): ?string
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
     * @param string $name
     * @return string
     */
    protected function getClassName(string $name): string
    {
        return $this->prefix.$name.$this->suffix;
    }
}
