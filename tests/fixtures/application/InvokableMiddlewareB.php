<?php


class InvokableMiddlewareB
{
    public function __invoke($request, \Psr\Http\Server\RequestHandlerInterface $handler)
    {
        return $handler->handle($request);
    }
}