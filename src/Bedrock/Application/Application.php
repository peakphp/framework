<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Bootstrap\Bootstrap;
use Peak\Bedrock\Http\Stack;
use Peak\Bedrock\Http\Request\Route;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Blueprint\Http\ResponseEmitter;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application
 * @package Peak\Bedrock
 */
class Application implements \Peak\Blueprint\Http\Application
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
     * @var Dictionary
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
     * @return Kernel
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function getProp(string $property, $default = null)
    {
        return $this->props->get($property, $default);
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasProp(string $property)
    {
        return $this->props->has($property);
    }

    /**
     * @return Dictionary
     */
    public function getProps()
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
    public function add($handler)
    {
        if (is_array($handler)) {
            $this->handlers = array_merge($this->handlers, $handler);
        } else {
            $this->handlers[] = $handler;
        }
        return $this;
    }

    /**
     * @param string $path
     * @param mixed $handlers
     * @return Route
     */
    public function get(string $path, $handlers): Route
    {
        return $this->createRoute('GET', $path, $handlers);
    }

    /**
     * @param string $path
     * @param mixed $handlers
     * @return Route
     */
    public function post(string $path, $handlers): Route
    {
        return $this->createRoute('POST', $path, $handlers);
    }

    /**
     * @param string $path
     * @param mixed $handlers
     * @return Route
     */
    public function all(string $path, $handlers): Route
    {
        return $this->createRoute(null, $path, $handlers);
    }

    /**
     * @param null|string $method
     * @param string $path
     * @param mixed $handlers
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
     *
     * @return $this
     */
    public function reset()
    {
        $this->handlers = [];
        return $this;
    }

    /**
     * Overwrite the current app stack
     *
     * @param mixed $handlers
     * @return $this
     */
    public function set($handlers)
    {
        $this->reset();
        $this->add($handlers);
        return $this;
    }

    /**
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
     *
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
     * Handle the request and emit a response
     *
     * @param ServerRequestInterface $request
     * @param ResponseEmitter $emitter
     * @return mixed
     */
    public function run(ServerRequestInterface $request, ResponseEmitter $emitter)
    {
        return $emitter->emit($this->handle($request));
    }
}
