<?php

declare(strict_types=1);

namespace Peak\Http\Request;

class RouteArgs
{
    /**
     * @var array
     */
    private $args;

    /**
     * RouteArgs constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
    }

    /**
     * @return array
     */
    public function raw()
    {
        return $this->args;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->args[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        return isset($this->args[$name]);
    }
}
