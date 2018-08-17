<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http;

use Peak\Blueprint\Resolvable;

/**
 * Class StackFactory
 * @package Peak\Bedrock\Http
 */
class StackFactory
{
    /**
     * @var Resolvable
     */
    private $handlerResolver;

    /**
     * StackFactory constructor.
     *
     * @param Resolvable $handlerResolver
     */
    public function __construct(Resolvable $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * @param array $handlers
     * @param Resolvable|null $handlerResolver
     * @return Stack
     */
    public function create(array $handlers, Resolvable $handlerResolver = null)
    {
        $handlerResolver = $handlerResolver ?? $this->handlerResolver;
        $stack = new Stack($handlers, $handlerResolver);
        return $stack;
    }
}