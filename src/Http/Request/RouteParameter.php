<?php

declare(strict_types=1);

namespace Peak\Http\Request;

class RouteParameter
{
    /**
     * @var array
     */
    private $params;

    /**
     * RouteFragments constructor.
     * @param array $params
     */
    /**
     * RouteParameter constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function raw()
    {
        return $this->params;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->params[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->params[$name]);
    }
}
