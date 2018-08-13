<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\Middleware\CallableMiddleware;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;

require_once FIXTURES_PATH.'/application/ResponseA.php';

/**
 * Class CallableMiddlewareTest
 */
class CallableMiddlewareTest extends TestCase
{
    public function testProcess()
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
}
