<?php

declare(strict_types=1);

namespace Peak\Http\Middleware;

use Exception;
use Peak\Http\Exception\JsonBodyParserException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class JsonBodyParserMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param Handler $handler
     * @return Response
     * @throws Exception
     */
    public function process(Request $request, Handler $handler): Response
    {
        if ($request->hasHeader('content-type') &&
            $request->getHeaderLine('content-type') === 'application/json') {

            $json = json_decode((string)$request->getBody(), true);
            if (json_last_error() !== 0) {
                throw new JsonBodyParserException(lcfirst(json_last_error_msg()));
            }
            $request = $request->withParsedBody($json);
        }
        // call the next
        return $handler->handle($request);
    }
}
