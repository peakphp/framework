<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http;

use Peak\Bedrock\AbstractApplication;
use Peak\Blueprint\Bedrock\HttpApplication;
use Peak\Http\Request\BlankRequest;
use Peak\Http\Request\PreRoute;
use Peak\Http\Stack;
use Peak\Http\Request\Route;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Blueprint\Http\ResponseEmitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge;
use function is_array;

class Application extends AbstractApplication implements HttpApplication
{
    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @var ResourceResolver
     */
    private $handlerResolver;

    /**
     * @var GroupManager
     */
    private $groupManager;

    /**
     * Application constructor.
     * @param Kernel $kernel
     * @param ResourceResolver $handlerResolver
     * @param Dictionary|null $props
     */
    public function __construct(
        Kernel $kernel,
        ResourceResolver $handlerResolver,
        Dictionary $props = null
    ) {
        $this->kernel = $kernel;
        $this->handlerResolver = $handlerResolver;
        $this->props = $props;
        $this->groupManager = new GroupManager();
    }

    /**
     * @return ResourceResolver
     */
    public function getHandlerResolver(): ResourceResolver
    {
        return $this->handlerResolver;
    }

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getFromContainer(string $id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Add something to application stack
     * @param $handlers
     * @return Application
     */
    public function stack($handlers): self
    {
        $handlersToAdd = [];
        (is_array($handlers))
            ? $handlersToAdd = $handlers
            : $handlersToAdd[] = $handlers;

        ($this->groupManager->currentlyInAGroup())
            ? $this->groupManager->holdHandlers($handlersToAdd)
            : $this->handlers = array_merge($this->handlers, $handlersToAdd);

        return $this;
    }

    /**
     * Conditional stacking
     * @param bool $condition
     * @param $handlers
     * @return Application
     */
    public function stackIfTrue(bool $condition, $handlers): self
    {
        if ($condition) {
            $this->stack($handlers);
        }
        return $this;
    }

    /**
     * @param string $path
     * @param callable $fn
     * @return Application
     */
    public function group(string $path, Callable $fn): self
    {
        $fullPath = $this->groupManager->getFullPathFor($path);

        $this->groupManager->startGroup($path);
        $fn();
        $stack = $this->createStack($this->groupManager->getHandlers($fullPath));
        $this->groupManager->releaseHandlers($fullPath);
        $this->groupManager->stopGroup($path);

        $this->stack(new PreRoute($fullPath, $stack));
        return $this;
    }

    /**
     * Stack a new GET route
     * @see stackRoute
     */
    public function get(string $path, $handlers): self
    {
        return $this->stackRoute('GET', $path, $handlers);
    }

    /**
     * Stack a new POST route
     * @see stackRoute()
     */
    public function post(string $path, $handlers): self
    {
        return $this->stackRoute('POST', $path, $handlers);
    }

    /**
     * Stack a new PUT route
     * @see stackRoute()
     */
    public function put(string $path, $handlers): self
    {
        return $this->stackRoute('PUT', $path, $handlers);
    }

    /**
     * Stack a new PATCH route
     * @see stackRoute()
     */
    public function patch(string $path, $handlers): self
    {
        return $this->stackRoute('PATCH', $path, $handlers);
    }

    /**
     * Stack a new DELETE route
     * @see stackRoute()
     */
    public function delete(string $path, $handlers): self
    {
        return $this->stackRoute('DELETE', $path, $handlers);
    }

    /**
     * Stack a new method less route
     * @see stackRoute()
     */
    public function all(string $path, $handlers): self
    {
        return $this->stackRoute(null, $path, $handlers);
    }

    /**
     * Create and stack a new route
     * @param string|null $method
     * @param string $path
     * @param mixed $handlers
     * @return Application
     */
    public function stackRoute(?string $method, string $path, $handlers): self
    {
        return $this->stack(
            $this->createRoute($method, $path, $handlers)
        );
    }

    /**
     * Create a new route
     * @param null|string $method
     * @param string $path
     * @param mixed $handlers
     * @return Route
     */
    public function createRoute(?string $method, string $path, $handlers): Route
    {
        if ($this->groupManager->currentlyInAGroup()) {
            $path = $this->groupManager->getFullPathFor($path);
        }
        if ($handlers instanceof \Peak\Blueprint\Http\Stack) {
            return new Route($method, $path, $handlers);
        }
        return new Route($method, $path, $this->createStack($handlers));
    }

    /**
     * Create a stack with the current app handlerResolver
     * @param mixed $handlers
     * @return Stack
     */
    public function createStack($handlers): Stack
    {
        if (!is_array($handlers)) {
            $handlers = [$handlers];
        }
        return new Stack(
            $handlers,
            $this->handlerResolver
        );
    }

    /**
     * Flush current app stack
     * @return Application
     */
    public function reset(): self
    {
        $this->handlers = [];
        return $this;
    }

    /**
     * Overwrite the current app stack
     * @param mixed $handlers
     * @return Application
     */
    public function set($handlers): self
    {
        $this->reset();
        $this->stack($handlers);
        return $this;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $stack = new Stack(
            $this->handlers,
            $this->handlerResolver
        );
        return $stack->handle($request);
    }

    /**
     * Run the stack with a request and emit the response
     * @param ServerRequestInterface $request
     * @param ResponseEmitter $emitter
     * @return mixed
     */
    public function run(ServerRequestInterface $request, ResponseEmitter $emitter)
    {
        return $emitter->emit($this->handle($request));
    }

    /**
     * Run the stack with a void request.
     * Useful for running a stack outside server request context
     *
     * @param ResponseEmitter $emitter
     * @return mixed
     */
    public function runDry(ResponseEmitter $emitter)
    {
        return $this->run(new BlankRequest(), $emitter);
    }
}
