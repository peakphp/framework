<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class CallableMiddleware
 * @package Peak\Bedrock\Http\Middleware
 */
class CallableMiddleware implements MiddlewareInterface
{
    /**
     * @var
     */
    private $callable;

    /**
     * CallableMiddleware constructor.
     *
     * @param $callable
     */
    public function __construct($callable)
    {
        $this->callable = $callable;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $fn = $this->callable;
        return $fn($request, $handler);
    }
}