<?php

declare(strict_types=1);

namespace Peak\Blueprint\Bedrock;

use Peak\Blueprint\Http\ResponseEmitter;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface HttpApplication extends Application, RequestHandlerInterface
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
