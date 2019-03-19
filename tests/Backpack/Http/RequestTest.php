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

        $request = $this->createServerRequest();
        $request
            ->method('getMethod')
            ->will($this->returnValue('POST'));
        $this->assertTrue(Request::isPost($request));

        $request = $this->createServerRequest();
        $request
            ->method('getMethod')
            ->will($this->returnValue('PUT'));
        $this->assertTrue(Request::isPut($request));

        $request = $this->createServerRequest();
        $request
            ->method('getMethod')
            ->will($this->returnValue('PATCH'));
        $this->assertTrue(Request::isPatch($request));

        $request = $this->createServerRequest();
        $request
            ->method('getMethod')
            ->will($this->returnValue('DELETE'));
        $this->assertTrue(Request::isDelete($request));
    }
}
