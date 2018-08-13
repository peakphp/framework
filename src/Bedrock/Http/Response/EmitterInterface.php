<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface EmitterInterface
 * @package Peak\Bedrock\Http\Response
 */
interface EmitterInterface
{
    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function emit(ResponseInterface $response);
}