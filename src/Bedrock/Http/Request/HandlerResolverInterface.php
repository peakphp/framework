<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Request;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface HandlerResolverInterface
 * @package Peak\Bedrock\Http\Request
 */
interface HandlerResolverInterface
{
    /**
     * @param mixed $handler
     * @return MiddlewareInterface|RequestHandlerInterface
     */
    public function resolve($handler);
}
