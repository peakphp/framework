<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\RouteArgs;
use \Peak\Http\Request\RouteServerRequest;
use \Psr\Http\Message\ServerRequestInterface;

class RouteServerRequestTest extends TestCase
{
    public function testGeneral()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->args = new RouteArgs(['test' => 123]);
        $rsr = new RouteServerRequest($request);

        $this->assertTrue($rsr->hasArg('test'));
        $this->assertFalse($rsr->hasArg('foobar'));

        $this->assertTrue($rsr->getParam('test') == 123);
        $this->assertTrue($rsr->getArg('foobar', 'test') === 'test');
    }

    public function testHasWithNoRouteParam()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $rsr = new RouteServerRequest($request);

        $this->assertFalse($rsr->hasArg('foobar'));
    }

    public function testHasStrict()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->args = new RouteArgs(['test' => 123]);
        $rsr = new RouteServerRequest($request);
        $this->assertFalse($rsr->hasArg('test', 'string'));
    }

    public function testInterfaceSignature()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $rsr = new RouteServerRequest($request);

        $request = $this->populateRequestMethods($request);

        $rsr->getProtocolVersion();
        $rsr->withProtocolVersion("");
        $rsr->getHeaders();
        $rsr->hasHeader("");
        $rsr->getHeader("");
        $rsr->getHeaderLine("");
        $rsr->withHeader("", "");
        $rsr->withAddedHeader("", "");
        $rsr->withoutHeader("");
        $rsr->getBody();
        $rsr->withBody($this->createMock(\Psr\Http\Message\StreamInterface::class));
        $rsr->getRequestTarget();
        $rsr->withRequestTarget("");
        $rsr->getMethod();
        $rsr->withMethod("");
        $rsr->getUri();
        $rsr->withUri($this->createMock(\Psr\Http\Message\UriInterface::class));
        $rsr->getServerParams();
        $rsr->getCookieParams();
        $rsr->withCookieParams([]);
        $rsr->getQueryParams();
        $rsr->withQueryParams([]);
        $rsr->getUploadedFiles();
        $rsr->withUploadedFiles([]);
        $rsr->getParsedBody();
        $rsr->withParsedBody("");
        $rsr->getAttributes();
        $rsr->getAttribute("", null);
        $rsr->withAttribute("", "");
        $rsr->withoutAttribute("");
        $this->assertInstanceOf(ServerRequestInterface::class, $rsr);

    }

    private function populateRequestMethods($request)
    {
        $methods = [
            'withProtocolVersion', 'withHeader', 'withAddedHeader', 'withoutHeader', 'withBody', 'withRequestTarget', 'withMethod', 'withUri',
            'withCookieParams', 'withQueryParams', 'withUploadedFiles', 'withParsedBody', 'withAttribute', 'withoutAttribute'
        ];
        foreach ($methods as $method) {
            $request->method($method)->willReturn($this->createMock(ServerRequestInterface::class));
        }
        return $request;
    }
}
