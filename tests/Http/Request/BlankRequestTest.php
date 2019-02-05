<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Http\Request\BlankRequest;


class BlankRequestTest extends TestCase
{
    public function testNull()
    {
        $blankRequest = new BlankRequest();
        $this->assertTrue(is_null($blankRequest->getAttribute('blabla')));
        $this->assertTrue(is_array($blankRequest->getAttributes()));
        $this->assertTrue(is_null($blankRequest->getBody()));
        $this->assertTrue(is_array($blankRequest->getCookieParams()));
        $this->assertTrue(is_array($blankRequest->getHeader('test')));
        $this->assertTrue(is_string($blankRequest->getHeaderLine('test')));
        $this->assertTrue(is_array($blankRequest->getHeaders()));
        $this->assertTrue(is_string($blankRequest->getMethod()));
        $this->assertTrue(is_null($blankRequest->getParsedBody()));
        $this->assertTrue(is_string($blankRequest->getProtocolVersion()));
        $this->assertTrue(is_array($blankRequest->getQueryParams()));
        $this->assertTrue(is_string($blankRequest->getRequestTarget()));
        $this->assertTrue(is_array($blankRequest->getServerParams()));
        $this->assertTrue(is_array($blankRequest->getUploadedFiles()));
        $this->assertTrue(is_null($blankRequest->getUri()));
        $this->assertTrue($blankRequest->withRequestTarget('test') instanceof BlankRequest);
        $this->assertTrue($blankRequest->withHeader('test', 'test') instanceof BlankRequest);
        $this->assertTrue($blankRequest->withAddedHeader('test', 'test') instanceof BlankRequest);
        $this->assertTrue($blankRequest->withoutHeader('test') instanceof BlankRequest);
        $this->assertTrue($blankRequest->withAttribute('test', 'test') instanceof BlankRequest);
        $this->assertTrue($blankRequest->withoutAttribute('test') instanceof BlankRequest);
        $this->assertTrue($blankRequest->withParsedBody('test') instanceof BlankRequest);
    }

}
