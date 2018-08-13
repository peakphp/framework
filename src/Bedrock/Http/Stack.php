<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http;

use Peak\Bedrock\Http\Exception\EmptyStackException;
use Peak\Bedrock\Http\Exception\StackEndedWithoutResponseException;
use Peak\Bedrock\Http\Request\Exception\InvalidHandlerException;
use Peak\Bedrock\Http\Request\HandlerResolverInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Stack
 * @package Peak\Bedrock\Http
 */
class Stack implements StackInterface
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
     * @var HandlerResolverInterface
     */
    private $handlerResolver;

    /**
     * @var Stack
     */
    private $parentStack = null;

    /**
     * Stack constructor.
     *
     * @param array $handlers
     * @param HandlerResolverInterface $handlerResolver
     */
    public function __construct(array $handlers, HandlerResolverInterface $handlerResolver)
    {
        if (empty($handlers)) {
            throw new EmptyStackException($this);
        }

        $this->handlers = $handlers;
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * @param StackInterface $parentStack
     */
    public function setParent(StackInterface $parentStack)
    {
        $this->parentStack = $parentStack;
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

        if ($this->nextHandler instanceof StackInterface) {
            $response = $this->handleStack($this->nextHandler, $request);
            if ($response !== false) {
                return $response;
            }
            $this->nextHandler = next($this->handlers);
        }

        if ($this->nextHandler === false && isset($this->parentStack)) {
            return $this->parentStack->process($request, $this->parentStack);
        } elseif ($this->nextHandler === false) {
            throw new StackEndedWithoutResponseException($this);
        }

        $handlerInstance = $this->handlerResolver->resolve($this->nextHandler);

        if ($handlerInstance instanceof MiddlewareInterface) {
            return $handlerInstance->process($request, $this);
        } elseif($handlerInstance instanceof RequestHandlerInterface) {
            return $handlerInstance->handle($request);
        }

        throw new InvalidHandlerException($handlerInstance);
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
     * @param StackInterface $stack
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    protected function handleStack(StackInterface $stack, ServerRequestInterface $request): ResponseInterface
    {
        $stack->setParent($this);
        return $stack->handle($request);
    }
}
