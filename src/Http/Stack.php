<?php

declare(strict_types=1);

namespace Peak\Http;

use Peak\Http\Exception\EmptyStackException;
use Peak\Http\Exception\StackEndedWithoutResponseException;
use Peak\Http\Request\Exception\InvalidHandlerException;
use Peak\Blueprint\Common\ResourceResolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Stack implements \Peak\Blueprint\Http\Stack
{
    /**
     * @var array
     */
    private $handlers;

    /**
     * @var mixed
     */
    private $nextHandler;

    /**
     * @var ResourceResolver
     */
    private $handlerResolver;

    /**
     * @var \Peak\Blueprint\Http\Stack
     */
    private $parentStack = null;

    /**
     * Stack constructor.
     *
     * @param array $handlers
     * @param ResourceResolver $handlerResolver
     */
    public function __construct(array $handlers, ResourceResolver $handlerResolver)
    {
        if (empty($handlers)) {
            throw new EmptyStackException($this);
        }

        $this->handlers = $handlers;
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * @param \Peak\Blueprint\Http\Stack $parentStack
     */
    public function setParent(\Peak\Blueprint\Http\Stack $parentStack)
    {
        $this->parentStack = $parentStack;
    }

    /**
     * @return \Peak\Blueprint\Http\Stack
     */
    public function getParent(): \Peak\Blueprint\Http\Stack
    {
        return $this->parentStack;
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
        $this->nextHandler = (isset($this->nextHandler)) ? next($this->handlers) : current($this->handlers);

        // nextHandler is a stack
        if ($this->nextHandler instanceof \Peak\Blueprint\Http\Stack) {
            $response = $this->handleStack($this->nextHandler, $request);
            if ($response !== false) {
                return $this->returnResponse($response);
            }
            $this->nextHandler = next($this->handlers);
        }

        // no more handlers, look for parent
        if ($this->nextHandler === false && isset($this->parentStack)) {
            return $this->returnResponse($this->parentStack->process($request, $this->parentStack));
        } elseif ($this->nextHandler === false) {
            throw new StackEndedWithoutResponseException($this);
        }

        // resolve nextHandler if not already a object
        $handlerInstance = $this->nextHandler;
        if (is_callable($handlerInstance) || !is_object($handlerInstance)) {
            $handlerInstance = $this->handlerResolver->resolve($this->nextHandler);
        }

        // how to call the handler (MiddlewareInterface or RequestHandlerInterface)
        if ($handlerInstance instanceof MiddlewareInterface) {
            return $this->returnResponse($handlerInstance->process($request, $this));
        } elseif($handlerInstance instanceof RequestHandlerInterface) {
            return $this->returnResponse($handlerInstance->handle($request));
        }

        // at this point, the handler is not good
        throw new InvalidHandlerException($this->nextHandler);
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
     * Handle a child stack
     *
     * @param \Peak\Blueprint\Http\Stack $stack
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    protected function handleStack(\Peak\Blueprint\Http\Stack $stack, ServerRequestInterface $request): ResponseInterface
    {
        $stack->setParent($this);
        return $stack->handle($request);
    }

    /**
     * Reset the stack before returning the response,
     * This allow the stack to be re-handle without throwing exception
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function returnResponse(ResponseInterface $response): ResponseInterface
    {
        $this->nextHandler = null;
        reset($this->handlers);
        return $response;
    }
}
