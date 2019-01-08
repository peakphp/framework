<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\BlankRequest;


class BlankRequestTest extends TestCase
{
    public function testNull()
    {
        $blankRequest = new BlankRequest();
        $this->assertNull($blankRequest->getAttribute('blabla'));
        $this->assertNull($blankRequest->getAttributes());
        $this->assertNull($blankRequest->getBody());
        $this->assertNull($blankRequest->getCookieParams());
        $this->assertNull($blankRequest->getHeader('test'));
        $this->assertNull($blankRequest->getHeaderLine('test'));
        $this->assertNull($blankRequest->getHeaders());
        $this->assertNull($blankRequest->getMethod());
        $this->assertNull($blankRequest->getParsedBody());
        $this->assertNull($blankRequest->getProtocolVersion());
        $this->assertNull($blankRequest->getQueryParams());
        $this->assertNull($blankRequest->getRequestTarget());
        $this->assertNull($blankRequest->getServerParams());
        $this->assertNull($blankRequest->getUploadedFiles());
        $this->assertNull($blankRequest->getUri());
        $this->assertNull($blankRequest->withRequestTarget('test'));
        $this->assertNull($blankRequest->withHeader('test', 'test'));
        $this->assertNull($blankRequest->withAddedHeader('test', 'test'));
        $this->assertNull($blankRequest->withoutHeader('test'));
        $this->assertNull($blankRequest->withAttribute('test', 'test'));
        $this->assertNull($blankRequest->withoutAttribute('test'));
        $this->assertNull($blankRequest->withParsedBody('test'));
    }

}
