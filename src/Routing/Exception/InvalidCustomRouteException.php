<?php

declare(strict_types=1);

namespace Peak\Routing\Exception;

/**
 * Class InvalidCustomRouteException
 * @package Peak\Routing\Exception
 */
class InvalidCustomRouteException extends \Exception
{
    /**
     * @var mixed
     */
    private $route;

    /**
     * InvalidCustomRouteException constructor.
     */
    public function __construct($route)
    {
        parent::__construct('Invalid custom route definition');
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }
}
