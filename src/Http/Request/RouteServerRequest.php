<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class RouteServerRequest implements ServerRequestInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var RouteParameter
     */
    private $routeParam;

    /**
     * ServerRequestWrapper constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        if (isset($request->param)) {
            $this->routeParam = $request->param;
        }
    }

    /**
     * @param string $name
     * @param string|null $type
     * @return bool
     */
    public function hasParam(string $name, string $type = null): bool
    {
        if (!isset($this->routeParam)) {
            return false;
        }
        $exists = isset($this->routeParam->$name);
        if (!$exists || !isset($type)) {
            return $exists;
        }

        return (gettype($this->routeParam->$name) === $type) ? true : false;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam(string $name)
    {
        if (!isset($this->routeParam)) {
            return null;
        }
        return $this->routeParam->$name;
    }

    /**
     * ---- end of wrapper  ----
     * The rest of methods below are only there for satisfying ServerRequestInterface
     */

    public function getProtocolVersion()
    {
        return $this->request->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        return new self($this->request->withProtocolVersion($version));
    }

    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->request->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->request->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->request->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        return new self($this->request->withHeader($name, $value));
    }

    public function withAddedHeader($name, $value)
    {
        return new self($this->request->withAddedHeader($name, $value));
    }

    public function withoutHeader($name)
    {
        return new self($this->request->withoutHeader($name));
    }

    public function getBody()
    {
        return $this->request->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        return new self($this->request->withBody($body));
    }

    public function getRequestTarget()
    {
        return $this->request->getRequestTarget();
    }

    public function withRequestTarget($requestTarget)
    {
        return new self($this->request->withRequestTarget($requestTarget));
    }

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function withMethod($method)
    {
        return new self($this->request->withMethod($method));
    }

    public function getUri()
    {
        return $this->request->getUri();
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new self($this->request->withUri($uri, $preserveHost));
    }

    public function getServerParams()
    {
        return $this->request->getServerParams();
    }

    public function getCookieParams()
    {
        return $this->request->getCookieParams();
    }

    public function withCookieParams(array $cookies)
    {
        return new self($this->request->withCookieParams($cookies));
    }

    public function getQueryParams()
    {
        return $this->request->getQueryParams();
    }

    public function withQueryParams(array $query)
    {
        return new self($this->request->withQueryParams($query));
    }

    public function getUploadedFiles()
    {
        return $this->request->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        return new self($this->request->withUploadedFiles($uploadedFiles));
    }

    public function getParsedBody()
    {
        return $this->request->getParsedBody();
    }

    public function withParsedBody($data)
    {
        return new self($this->request->withParsedBody($data));
    }

    public function getAttributes()
    {
        return $this->request->getAttributes();
    }

    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    public function withAttribute($name, $value)
    {
        return new self($this->request->withAttribute($name, $value));
    }

    public function withoutAttribute($name)
    {
        return new self($this->request->withoutAttribute($name));
    }
}