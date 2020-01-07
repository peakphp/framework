<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use Peak\Http\Exception\StackEndedWithoutResponseException;
use Peak\Blueprint\Http\Stack;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function preg_match;
use function strtoupper;
use function trim;

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
     * @var array
     */
    private $matches = [];

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

        // compile pseudo route syntax {param} and {param}:type into valid regex
        $routeRegex = (new RouteExpression($this->path))->getRegex();

        // look to match the route
        $this->pregMatch('#^'.$routeRegex.'$#', $request->getUri()->getPath());
        return !empty($this->matches);
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
        $isMatching = $this->match($request);

        // add regex matches(aka route arguments) to the request.
        $routeArgs = new RouteArgs($this->matches);
        foreach ($routeArgs->toArray() as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }
        // $request->args works but "pollute" the request object and will be removed in the next major version.
        // use the PSR-7 method $request->getAttribute() instead for retrieving an route argument
        $request->args = $routeArgs;

        if (!$isMatching) {
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

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->stack->getHandlers();
    }

    /**
     * @return array
     */
    protected function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @param string $pattern
     * @param string $path
     * @return array
     */
    protected function pregMatch(string $pattern, string $path): array
    {
        preg_match($pattern, $path, $this->matches);
        return $this->matches;
    }
}
