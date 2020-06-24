<?php

declare(strict_types=1);

namespace Peak\Blueprint\Http;

use Psr\Http\Message\ResponseInterface;

interface ResponseMiddleware
{
    /**
     * @param ResponseInterface $response
     * @param ResponseMiddleware $responseMiddleware
     * @return ResponseInterface
     */
    public function process(ResponseInterface $response, ResponseMiddleware $responseMiddleware): ResponseInterface;
}
