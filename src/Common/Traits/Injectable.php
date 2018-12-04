<?php

namespace Peak\Common\Traits;

use \Closure;
use \RunTimeException;

/**
 * Trait Injectable
 * @package Peak\Common\Traits
 */
trait Injectable
{
    /**
     * @var array
     */
    private $methods = [];

    /**
     * Add a "method"
     * @param string $methodName
     * @param callable $methodCallable
     * @return $this
     */
    public function addMethod(string $methodName, Closure $methodCallable)
    {
        $this->methods[$methodName] = Closure::bind($methodCallable, $this, get_class());
        return $this;
    }

    /**
     * Add a "method"
     * @param string $methodName
     * @param callable $methodCallable
     * @return $this
     */
    public function addMethodFromCallable(string $methodName, callable $methodCallable)
    {
        $closure = Closure::fromCallable($methodCallable);
        $this->methods[$methodName] = Closure::bind($closure, $this, get_class());
        return $this;
    }

    /**
     * Search in methods
     *
     * @param string $methodName
     * @param array $args
     * @return mixed
     */
    public function __call(string $methodName, array $args)
    {
        if (isset($this->methods[$methodName])) {
            return call_user_func_array($this->methods[$methodName], $args);
        }

        throw new RuntimeException('There is no method with the given name to call');
    }
}
