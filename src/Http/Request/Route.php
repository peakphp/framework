<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use Peak\Http\Exception\StackEndedWithoutResponseException;
use Peak\Blueprint\Http\Stack;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Route implements \Peak\Blueprint\Http\Route, Stack
{
    /**
     * @var string|null
     */
    private $method;

    /**
     * @var string
     */
    private $path;

    /**
     * @var Stack
     */
    private $stack;

    /**
     * @var Stack
     */
    private $parentStack;

    /**
     * Route constructor.
     * @param string|null $method
     * @param string $path
     * @param Stack $stack
     */
    public function __construct(?string $method, string $path, Stack $stack)
    {
        $this->method = isset($method) ? strtoupper(trim($method)) : null;
        $this->path = $path;
        $this->stack = $stack;
    }

    /**
     * @param Stack $parentStack
     */
    public function setParent(Stack $parentStack)
    {
        $this->parentStack = $parentStack;
        $this->stack->setParent($parentStack);
    }

    /**
     * @return Stack
     */
    public function getParent(): Stack
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
        return !empty($matches);
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
     * @return string|null
     */
    public function getMethod(): ?string
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
