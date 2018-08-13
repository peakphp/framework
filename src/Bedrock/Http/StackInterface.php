<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface StackInterface
 * @package Peak\Bedrock\Http
 */
interface StackInterface extends RequestHandlerInterface, MiddlewareInterface
{
    public function setParent(StackInterface $parentStack);
}
