<?php

declare(strict_types=1);

namespace Peak\Backpack\Http;

use Psr\Http\Message\ServerRequestInterface;

class Request
{
    public static function isGet(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'GET');
    }

    public static function isPost(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'POST');
    }

    public static function isPut(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'PUT');
    }

    public static function isPatch(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'PATCH');
    }

    public static function isDelete(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'DELETE');
    }
}
