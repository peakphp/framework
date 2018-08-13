<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http;

use Peak\Bedrock\Http\Request\HandlerResolverInterface;

/**
 * Class StackFactory
 * @package Peak\Bedrock\Http
 */
class StackFactory
{
    /**
     * @var HandlerResolverInterface
     */
    private $handlerResolver;

    /**
     * StackFactory constructor.
     *
     * @param HandlerResolverInterface $handlerResolver
     */
    public function __construct(HandlerResolverInterface $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * @param array $handlers
     * @param HandlerResolverInterface|null $handlerResolver
     * @return Stack
     */
    public function create(array $handlers, HandlerResolverInterface $handlerResolver = null)
    {
        $handlerResolver = $handlerResolver ?? $this->handlerResolver;
        $stack = new Stack($handlers, $handlerResolver);
        return $stack;
    }
}