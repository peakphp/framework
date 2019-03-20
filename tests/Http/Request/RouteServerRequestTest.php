<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\RouteParameter;
use \Peak\Http\Request\RouteServerRequest;
use \Psr\Http\Message\ServerRequestInterface;

class RouteServerRequestTest extends TestCase
{
    public function testGeneral()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->param = new RouteParameter(['test' => 123]);
        $rsr = new RouteServerRequest($request);

        $this->assertTrue($rsr->hasParam('test'));
        $this->assertFalse($rsr->hasParam('foobar'));

        $this->assertTrue($rsr->getParam('test') == 123);
        $this->assertTrue($rsr->getParam('foobar', 'test') === 'test');
    }
}
