<?php

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

trait AssertRequest
{
    protected function assertRequestBody($app, ?string $method, string $path, string $expectedBody)
    {
        $request = $this->createRequest($method, $path);
        $result = $app->handle($request);
        $this->assertTrue((string)$result->getBody() === $expectedBody);
    }
}