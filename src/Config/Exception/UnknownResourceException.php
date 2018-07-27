<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

/**
 * Class UnknownResourceException
 * @package Peak\Config\Exception
 */
class UnknownResourceException extends \Exception
{
    /**
     * @var mixed
     */
    private $resource;

    /**
     * UnknownTypeException constructor.
     * @param mixed $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
        parent::__construct('Unknown config resources');
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
