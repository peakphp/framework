<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Peak\Bedrock\Http\Stack;
use Peak\Bedrock\Http\Request\HandlerResolverInterface;
use Peak\Bedrock\Http\Request\Route;
use Peak\Bedrock\Http\Response\EmitterInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Application
 * @package Peak\Bedrock
 */
class Application implements RequestHandlerInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @var string
     */
    private $version;

    /**
     * @var HandlerResolverInterface
     */
    private $handlerResolver;

    /**
     * Application constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(
        KernelInterface $kernel,
        HandlerResolverInterface $handlerResolver,
        string $version = '1.0'
    ) {
        $this->kernel = $kernel;
        $this->handlerResolver = $handlerResolver;
        $this->version = $version;
    }

    /**
     * @return HandlerResolverInterface
     */
    public function getHandlerResolver()
    {
        return $this->handlerResolver;
    }

    /**
     * @return KernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
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
     * @param $handlers
     * @return Route
     */
    public function get(string $path, $handlers): Route
    {
        return $this->addRoute('GET', $path, $handlers);
    }

    /**
     * @param string $path
     * @param $handlers
     * @return Route
     */
    public function post(string $path, $handlers): Route
    {
        return $this->addRoute('POST', $path, $handlers);
    }

    /**
     * @param string $path
     * @param $handlers
     * @return Route
     */
    public function all(string $path, $handlers): Route
    {
        return $this->addRoute(null, $path, $handlers);
    }

    /**
     * @param string $method
     * @param string $path
     * @param $handlers
     * @return Route
     */
    public function addRoute(?string $method, string $path, $handlers)
    {
        $stack = $handlers;
        if (is_array($handlers)) {
            $stack = new Stack($handlers, $this->handlerResolver);
        }
        return new Route($method, $path, $stack);
    }

    /**
     *
     * @return $this
     */
    public function reset()
    {
        $this->handlers = [];
        return $this;
    }

    /**
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
     * @param ServerRequestInterface $request
     * @param EmitterInterface $emitter
     * @return mixed
     */
    public function run(ServerRequestInterface $request, EmitterInterface $emitter)
    {
        return $emitter->emit($this->handle($request));
    }
}
