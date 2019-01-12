<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Bootstrap\Bootstrap;
use Peak\Http\Request\BlankRequest;
use Peak\Http\Stack;
use Peak\Http\Request\Route;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Blueprint\Http\ResponseEmitter;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application implements \Peak\Blueprint\Bedrock\Application
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @var ResourceResolver
     */
    private $handlerResolver;

    /**
     * @var Dictionary|null
     */
    private $props;

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
    }

    /**
     * @return ResourceResolver
     */
    public function getHandlerResolver()
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
     * @return Kernel
     */
    public function getKernel(): Kernel
    {
        return $this->kernel;
    }

    /**
     * @param string $property
     * @param mixed $default
     * @return mixed
     * @throws \Exception
     */
    public function getProp(string $property, $default = null)
    {
        if (!isset($this->props)) {
            throw new \Exception('Application properties is not defined! Cannot use getProp()');
        }
        return $this->props->get($property, $default);
    }

    /**
     * @param string $property
     * @return bool
     * @throws \Exception
     */
    public function hasProp(string $property): bool
    {
        if (!isset($this->props)) {
            throw new \Exception('Application properties is not defined! Cannot use hasProp()');
        }
        return $this->props->has($property);
    }

    /**
     * @return null|Dictionary
     */
    public function getProps(): ?Dictionary
    {
        return $this->props;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function getFromContainer(string $path)
    {
        return $this->getContainer()->get($path);
    }

    /**
     * @param mixed $handler
     * @return $this
     */
    public function stack($handler)
    {
        if (is_array($handler)) {
            $this->handlers = array_merge($this->handlers, $handler);
        } else {
            $this->handlers[] = $handler;
        }
        return $this;
    }

    /**
     * Stack a new GET route
     * @see stackRoute()
     */
    public function get(string $path, $handlers)
    {
        return $this->stackRoute('GET', $path, $handlers);
    }

    /**
     * Stack a new POST route
     * @see stackRoute()
     */
    public function post(string $path, $handlers)
    {
        return $this->stackRoute('POST', $path, $handlers);
    }

    /**
     * Stack a new PUT route
     * @see stackRoute()
     */
    public function put(string $path, $handlers)
    {
        return $this->stackRoute('PUT', $path, $handlers);
    }

    /**
     * Stack a new PATCH route
     * @see stackRoute()
     */
    public function patch(string $path, $handlers)
    {
        return $this->stackRoute('PATCH', $path, $handlers);
    }

    /**
     * Stack a new DELETE route
     * @see stackRoute()
     */
    public function delete(string $path, $handlers)
    {
        return $this->stackRoute('DELETE', $path, $handlers);
    }

    /**
     * Stack a new method less route
     * @see stackRoute()
     */
    public function all(string $path, $handlers)
    {
        return $this->stackRoute(null, $path, $handlers);
    }

    /**
     * Create and stack a new route
     * @param string|null $method
     * @param string $path
     * @param $handlers
     * @return Application
     */
    public function stackRoute(?string $method, string $path, $handlers)
    {
        return $this->stack(
            $this->createRoute($method, $path, $handlers)
        );
    }

    /**
     * Create a new route
     * @param null|string $method
     * @param string $path
     * @param $handlers
     * @return Route
     */
    public function createRoute(?string $method, string $path, $handlers): Route
    {
        if ($handlers instanceof \Peak\Blueprint\Http\Stack) {
            return new Route($method, $path, $handlers);
        }
        if (!is_array($handlers)) {
            $handlers = [$handlers];
        }
        return new Route($method, $path, new Stack($handlers, $this->getHandlerResolver()));
    }

    /**
     * Flush current app stack
     * @return $this
     */
    public function reset()
    {
        $this->handlers = [];
        return $this;
    }

    /**
     * Overwrite the current app stack
     * @param mixed $handlers
     * @return $this
     */
    public function set($handlers)
    {
        $this->reset();
        $this->stack($handlers);
        return $this;
    }

    /**
     * Bootstrap bootable processes
     * @param array $processes
     * @return $this
     * @throws \Peak\Bedrock\Bootstrap\Exception\InvalidBootableProcessException
     * @throws \ReflectionException
     */
    public function bootstrap(array $processes)
    {
        $bootstrap = new Bootstrap($processes, $this->getContainer());
        $bootstrap->boot();
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
