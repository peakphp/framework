<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \Closure;

/**
 * Class CallableMiddleware
 * @package Peak\Bedrock\Http\Middleware
 */
class CallableMiddleware implements MiddlewareInterface
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * CallableMiddleware constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
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
        if (!$this->callable instanceof Closure) {
            $return = call_user_func_array($fn, [$request, $handler]);
            if (null === $return) {
                return $handler->handle($request);
            } else {
                return $return;
            }
//            $fn();
        }
        return $fn($request, $handler);
    }
}