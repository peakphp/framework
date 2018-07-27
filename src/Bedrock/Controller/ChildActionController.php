<?php

declare(strict_types=1);

namespace Peak\Bedrock\Controller;

use Peak\Bedrock\Application;
use \Exception;

/**
 * For standalone controller action class
 */
abstract class ChildActionController
{
    /**
     * Parent controller
     * @var \Peak\Bedrock\Controller\ParentController
     */
    protected $parent;

    /**
     * ChildActionController constructor.
     *
     * @param ParentController $parent
     * @throws Application\Exceptions\InstanceNotFoundException
     * @throws Application\Exceptions\MissingContainerException
     */
    public function __construct(ParentController $parent)
    {
        $this->parent = $parent;

        // call child process() with di
        Application::container()->call(
            [$this, 'process']
        );
    }

    /**
     * Check method exists in parent
     *
     * @param $method
     * @param null $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $args = null)
    {
        if (!method_exists($this->parent, $method)) {
            $line = debug_backtrace()[0]['line'];
            throw new Exception('Method '.$method.'() not found in '.get_class($this).' on line '.$line);
        }
        return call_user_func_array([$this->parent, $method], $args);
    }

    /**
     * Check in ParentController for unknown property
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->parent->$name)) {
            return $this->parent->$name;
        }
        // throw default undefined property notice
        return $this->$name;
    }

    /**
     * Internal isset for ParentController property
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->parent->$name);
    }
}
