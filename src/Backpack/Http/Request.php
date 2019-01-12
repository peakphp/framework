<?php

namespace Peak\Backpack\Http;

use Psr\Http\Message\ServerRequestInterface;

class Request
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public static function isGet(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'GET');
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public static function isPost(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'POST');
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public static function isPut(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'PUT');
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public static function isPatch(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'PATCH');
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public static function isDelete(ServerRequestInterface $request): bool
    {
        return ($request->getMethod() === 'DELETE');
    }
}