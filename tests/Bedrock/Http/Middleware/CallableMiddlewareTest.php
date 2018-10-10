<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\Middleware\CallableMiddleware;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;

require_once FIXTURES_PATH.'/application/ResponseA.php';
require_once FIXTURES_PATH.'/application/InvokableMiddleware.php';

/**
 * Class CallableMiddlewareTest
 */
class CallableMiddlewareTest extends TestCase
{
    public function testClosure()
    {
        $closure = function($request, $handler) {
            return new ResponseA();
        };

        $callableMiddleware = new CallableMiddleware($closure);
        $response = $callableMiddleware->process(
            $this->createMock(ServerRequestInterface::class),
            $this->createMock(RequestHandlerInterface::class)
        );

        $this->assertInstanceOf(ResponseA::class, $response);
    }

    public function testInvoke()
    {
        $callableMiddleware = new CallableMiddleware(new InvokableMiddleware());
        $response = $callableMiddleware->process(
            $this->createMock(ServerRequestInterface::class),
            $this->createMock(RequestHandlerInterface::class)
        );

        $this->assertInstanceOf(ResponseA::class, $response);
    }
}

