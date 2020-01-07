<?php

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

trait RequestFactory
{
    public function createRequest(?string $method, string $path)
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $request->method('withAttribute')
            ->willReturn($request);

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->any()))
            ->method('getPath')
            ->willReturn($path);

        $request->expects($this->any())
            ->method('getUri')
            ->willReturn($uri);

        $request->expects($this->any())
            ->method('getMethod')
            ->willReturn($method);

        return $request;
    }
}