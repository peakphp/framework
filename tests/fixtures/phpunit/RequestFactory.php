<?php

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

trait RequestFactory
{
    public function createRequest(?string $method, string $path)
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->atLeastOnce()))
            ->method('getPath')
            ->willReturn($path);

        $request->expects($this->atLeastOnce())
            ->method('getUri')
            ->willReturn($uri);

        $request->expects($this->any())
            ->method('getMethod')
            ->willReturn($method);

        return $request;
    }
}