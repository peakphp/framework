<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Http\Application;
use \Peak\Bedrock\Kernel;
use \Peak\Http\Request\HandlerResolver;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\UriInterface;

require_once FIXTURES_PATH . '/phpunit/RequestFactory.php';
require_once FIXTURES_PATH . '/phpunit/AssertRequest.php';

class ApplicationGroupTest extends TestCase
{
    use RequestFactory;
    use AssertRequest;

    protected function createApp($kernel = null, $handlerResolver = null, $props = null)
    {
        return new Application(
            $kernel ?? $this->createMock(Kernel::class),
            $handlerResolver ?? $this->createMock(HandlerResolver::class),
            $props ?? null
        );
    }

    public function testRouteGroup()
    {
        $app = $this->createApp();

        $app
            ->group('/users', function() use ($app) {
                $app
                    ->group('/action', function() use ($app) {
                        $app
                            ->stack(new MiddlewareA())
                            ->get('/edit', new HandlerB())
                            ->get('/update', new HandlerB())
                            ->get('/cancel', new HandlerB());
                    })
                    ->get('/mypath1', new HandlerA())
                    ->get('/mypath2', new HandlerA())
                    ->get('/mypath3', new HandlerA())
                    ->put('/mypath3', new HandlerA())
                    ->patch('/mypath3', new HandlerA())
                    ->delete('/mypath3', new HandlerA())
                    ->all('/mypath3', new HandlerA());

                $app
                    ->group('/users', function() use ($app) {
                        $app->get('/mypath1', new HandlerA());
                        $app->group('/users', function() use ($app) {
                            $app->group('/users', function() use ($app) {
                                $app->group('/users', function() use ($app) {
                                    $app->get('/mypath1', new HandlerB());
                                });
                            });
                        });
                    });

            })
            ->group('/products', function() use ($app) {
                $app
                    ->stack(new MiddlewareA())
                    ->get('/mypath1', new HandlerB())
                    ->post('/mypath2', new HandlerB())
                    ->get('/mypath3', new HandlerB());

            })
            ->group( '/users/{id}:num', function() use ($app) {
                $app->get('/profile', new HandlerB());
            })
            ->stack(new HandlerC());


        $this->assertRequestBody($app, 'GET', '/users/action/edit', 'ResponseB');
        $this->assertRequestBody($app, 'GET', '/users/mypath3', 'ResponseA');
        $this->assertRequestBody($app, 'GET', '/products/mypath3', 'ResponseB');
        $this->assertRequestBody($app, 'POST', '/products/mypath2', 'ResponseB');
        $this->assertRequestBody($app, 'GET', '/products/mypath2', 'ResponseC');
        $this->assertRequestBody($app, 'GET', '/unknown-url', 'ResponseC');
        $this->assertRequestBody($app, 'GET', '/users/3/profile', 'ResponseB');
        $this->assertRequestBody($app, 'GET', '/users/234234/profile', 'ResponseB');
        $this->assertRequestBody($app, 'GET', '/users/sdfsdfsdfsd/profile', 'ResponseC');
        $this->assertRequestBody($app, 'GET', '/users/users/mypath1', 'ResponseA');
        $this->assertRequestBody($app, 'GET', '/users/users/users/users/users/mypath1', 'ResponseB');

    }

}
