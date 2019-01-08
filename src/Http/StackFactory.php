<?php

declare(strict_types=1);

namespace Peak\Http;

use Peak\Blueprint\Common\ResourceResolver;

class StackFactory
{
    /**
     * @var ResourceResolver
     */
    private $handlerResolver;

    /**
     * StackFactory constructor.
     *
     * @param ResourceResolver $handlerResolver
     */
    public function __construct(ResourceResolver $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * @param array $handlers
     * @param ResourceResolver|null $handlerResolver
     * @return Stack
     */
    public function create(array $handlers, ResourceResolver $handlerResolver = null)
    {
        $handlerResolver = $handlerResolver ?? $this->handlerResolver;
        $stack = new Stack($handlers, $handlerResolver);
        return $stack;
    }
}