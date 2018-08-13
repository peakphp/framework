<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Middleware\Exception;

/**
 * Class UnresolvableMiddlewareException
 * @package Peak\Bedrock\Http\Middleware\Exception
 */
class UnresolvableMiddlewareException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $middleware;

    /**
     * UnresolvableMiddlewareException constructor.
     *
     * @param mixed $middleware
     */
    public function __construct($middleware)
    {
        parent::__construct('Cannot resolve middleware');
        $this->middleware = $middleware;
    }

    /**
     * @return mixed
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }
}
