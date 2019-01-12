<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Backpack\Http\Request;
use \Psr\Http\Message\ServerRequestInterface;

class RequestTest extends TestCase
{
    protected function createServerRequest()
    {
        return $this->createMock(ServerRequestInterface::class);
    }

    public function testIsMethod()
    {
        $request = $this->createServerRequest();
        $request
            ->method('getMethod')
            ->will($this->returnValue('POST'));

        $this->assertFalse(Request::isGet($request));

        $request = $this->createServerRequest();
        $request
            ->method('getMethod')
            ->will($this->returnValue('GET'));
        $this->assertTrue(Request::isGet($request));
    }
}
