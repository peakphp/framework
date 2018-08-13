<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Middleware\Exception;

/**
 * Class MiddlewareNotFoundException
 * @package Peak\Bedrock\Http\Middleware\Exception
 */
class MiddlewareNotFoundException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $middleware;

    /**
     * MiddlewareNotFoundException constructor.
     *
     * @param string $middleware
     */
    public function __construct(string $middleware)
    {
        parent::__construct('Middleware '.$middleware.' not found');
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
