<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Peak\Bedrock\Http\Request\HandlerResolver;
use Peak\Bedrock\Http\Stack;
use Peak\Bedrock\Http\Response\Emitter;
use Peak\Di\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application
 *
 * Useful for quick application prototyping.
 * DO NOT USE FOR A REAL APP IN PRODUCTION UNLESS YOU KNOW WHAT YOU ARE DOING
 *
 * @package Peak\Bedrock
 */
class BlackMagic
{
    /**
     * Create an app!
     *
     * @param string $environment
     * @param string|null $version
     * @return Application
     */
    public static function createApp(string $environment = 'dev', string $version = '1.0')
    {
        $container = new Container();
        $kernel = new Kernel($environment, $container);
        $handlerResolver = new HandlerResolver($container);
        return new Application(
            $kernel,
            $handlerResolver,
            $version
        );
    }

    /**
     * Create a stack!
     *
     * @param Application $application
     * @param array $middlewares
     * @return Stack
     */
    public static function createAppStack(Application $application, array $middlewares)
    {
        return new Stack($middlewares, $application->getHandlerResolver());
    }

    /**
     * Emit a response !
     *
     * @param ResponseInterface $response
     * @return bool|mixed
     */
    public static function emit(ResponseInterface $response)
    {
        return (new Emitter())->emit($response);
    }

    /**
     * Handle And Emit !
     *
     * @param Application $app
     * @param array $middlewares
     * @param ServerRequestInterface $request
     */
    public static function run(Application $app, ServerRequestInterface $request)
    {
        return $app->run($request, new Emitter());
    }

    /**
     * Handle And Emit !
     *
     * @param Application $app
     * @param array $middlewares
     * @param ServerRequestInterface $request
     */
    public static function runThis(Application $app, array $middlewares, ServerRequestInterface $request)
    {
        $app->set($middlewares)
            ->run($request, new Emitter());
    }
}