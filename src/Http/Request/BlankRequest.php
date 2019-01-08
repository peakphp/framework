<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class BlankRequest implements ServerRequestInterface
{
    public function getProtocolVersion()
    {
        return null;
    }

    public function withProtocolVersion($version)
    {
        return null;
    }

    public function getHeaders()
    {
        return null;
    }

    public function hasHeader($name)
    {
        return null;
    }

    public function getHeader($name)
    {
        return null;
    }

    public function getHeaderLine($name)
    {
        return null;
    }

    public function withHeader($name, $value)
    {
        return null;
    }

    public function withAddedHeader($name, $value)
    {
        return null;
    }

    public function withoutHeader($name)
    {
        return null;
    }

    public function getBody()
    {
        return null;
    }

    public function withBody(StreamInterface $body)
    {
        return null;
    }

    public function getRequestTarget()
    {
        return null;
    }

    public function withRequestTarget($requestTarget)
    {
        return null;
    }

    public function getMethod()
    {
        return null;
    }

    public function withMethod($method)
    {
        return null;
    }

    public function getUri()
    {
        return null;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return null;
    }

    public function getServerParams()
    {
        return null;
    }

    public function getCookieParams()
    {
        return null;
    }

    public function withCookieParams(array $cookies)
    {
        return null;
    }

    public function getQueryParams()
    {
        return null;
    }

    public function withQueryParams(array $query)
    {
        return null;
    }

    public function getUploadedFiles()
    {
        return null;
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        return null;
    }

    public function getParsedBody()
    {
        return null;
    }

    public function withParsedBody($data)
    {
        return null;
    }

    public function getAttributes()
    {
        return null;
    }

    public function getAttribute($name, $default = null)
    {
        return null;
    }

    public function withAttribute($name, $value)
    {
        return null;
    }

    public function withoutAttribute($name)
    {
        return null;
    }
}
