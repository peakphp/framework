<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Request;

use Peak\Bedrock\Http\Exception\StackEndedWithoutResponseException;
use Peak\Bedrock\Http\StackInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Route
 * @package Peak\Bedrock\Http\Request
 */
class Route implements StackInterface
{
    /**
     * @var null|string
     */
    private $method;

    /**
     * @var string
     */
    private $path;

    /**
     * @var StackInterface
     */
    private $stack;

    /**
     * @var StackInterface
     */
    private $parentStack;

    /**
     * Route constructor.
     *
     * @param null|string $method
     * @param string $path
     * @param StackInterface $stack
     */
    public function __construct(?string $method, string $path, StackInterface $stack)
    {
        $this->method = $method;
        $this->path = $path;
        $this->stack = $stack;
    }

    /**
     * @param StackInterface $parentStack
     */
    public function setParent(StackInterface $parentStack)
    {
        $this->parentStack = $parentStack;
        $this->stack->setParent($parentStack);
    }

    /**
     * @return StackInterface
     */
    public function getParent(): StackInterface
    {
        return $this->parentStack;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function match(ServerRequestInterface $request): bool
    {
        if (null !== $this->method && $this->method !== $request->getMethod()) {
            return false;
        }

        preg_match('#^'.$this->path.'$#', $request->getUri()->getPath(), $matches);
        if (empty($matches)) {
            return false;
        }
        return true;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->match($request)) {
            return $this->processParent($request, $handler);
        }

        return $this->stack->handle($request);
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->process($request, $this);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function processParent(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!isset($this->parentStack)) {
            throw new StackEndedWithoutResponseException($this);
        }
        return $this->parentStack->process($request, $handler);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
