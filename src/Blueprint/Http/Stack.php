<?php

declare(strict_types=1);

namespace Peak\Blueprint\Http;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface Stack extends RequestHandlerInterface, MiddlewareInterface
{
    /**
     * @param Stack $parentStack
     */
    public function setParent(Stack $parentStack);

    /**
     * @return Stack
     */
    public function getParent(): Stack;

    /**
     * @return array
     */
    public function getHandlers(): array;
}
