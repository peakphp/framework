<?php

declare(strict_types=1);

namespace Peak\Blueprint\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface Stack
 * @package Peak\Blueprint\Http
 */
interface Application extends RequestHandlerInterface
{
    /**
     * Handle the request and emit a response
     *
     * @param ServerRequestInterface $request
     * @param ResponseEmitter $emitter
     * @return mixed
     */
    public function run(ServerRequestInterface $request, ResponseEmitter $emitter);
}
