<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Middleware\JsonBodyParserMiddleware;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseInterface;

class JsonBodyParserMiddlewareTest extends TestCase
{
    public function testJsonBody()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('hasHeader')
            ->willReturn(true);
        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('application/json');
        $request
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('{"id":9}');
        $request
            ->expects($this->once())
            ->method('withParsedBody')
            ->willReturn($this->createMock(ServerRequestInterface::class));

        $nextHandler = $this->createMock(RequestHandlerInterface::class);
        $nextHandler
            ->expects($this->once())
            ->method('handle')
            ->willReturn($this->createMock(ResponseInterface::class));



        $middleware = new JsonBodyParserMiddleware();
        $response = $middleware->process($request, $nextHandler);


        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testBodyParserException()
    {
        $this->expectException(\Peak\Http\Exception\BodyParserException::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('hasHeader')
            ->willReturn(true);
        $request
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('application/json');
        $request
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(' 123423423 "');

        $nextHandler = $this->createMock(RequestHandlerInterface::class);
        $middleware = new JsonBodyParserMiddleware();
        $middleware->process($request, $nextHandler);
    }
}

