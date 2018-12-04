<?php

declare(strict_types=1);

namespace Peak\Blueprint\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ResponseMiddleware
 * @package Peak\Blueprint\Http
 */
interface ResponseMiddleware
{
    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function process(ResponseInterface $response, ResponseMiddleware $responseMiddleware): ResponseInterface;
}