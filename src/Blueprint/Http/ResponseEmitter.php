<?php

declare(strict_types=1);

namespace Peak\Blueprint\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ResponseEmitter
 * @package Peak\Blueprint\Http
 */
interface ResponseEmitter
{
    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function emit(ResponseInterface $response);
}