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

    public function testHasWithNoRouteParam()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $rsr = new RouteServerRequest($request);

        $this->assertFalse($rsr->hasParam('foobar'));
    }

    public function testHasStrict()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->param = new RouteParameter(['test' => 123]);
        $rsr = new RouteServerRequest($request);
        $this->assertFalse($rsr->hasParam('test', 'string'));
    }

    public function testInterfaceSignature()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $rsr = new RouteServerRequest($request);

        $rsr->getProtocolVersion();
        //$rsr->withProtocolVersion("");
        $rsr->getHeaders();
        $rsr->hasHeader("");
        $rsr->getHeader("");
        $rsr->getHeaderLine("");
        //$rsr->withHeader("", "");
        //$rsr->withAddedHeader("", "");
        //$rsr->withoutHeader("");
        $rsr->getBody();
        //$rsr->withBody($this->createMock(\Psr\Http\Message\StreamInterface::class));
        $rsr->getRequestTarget();
        //$rsr->withRequestTarget("");
        $rsr->getMethod();
        //$rsr->withMethod("");
        $rsr->getUri();
        //$rsr->withUri($this->createMock(\Psr\Http\Message\UriInterface::class));
        $rsr->getServerParams();
        $rsr->getCookieParams();
        //$rsr->withCookieParams([]);
        $rsr->getQueryParams();
        //$rsr->withQueryParams([]);
        $rsr->getUploadedFiles();
        //$rsr->withUploadedFiles([]);
        $rsr->getParsedBody();
        //$rsr->withParsedBody("");
        $rsr->getAttributes();
        $rsr->getAttribute("", null);
        //$rsr->withAttribute("", "");
        //$rsr->withoutAttribute("");
        $this->assertInstanceOf(ServerRequestInterface::class, $rsr);

    }
}
