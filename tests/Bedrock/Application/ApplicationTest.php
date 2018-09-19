<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Application\Application;
use \Peak\Bedrock\Kernel;
use \Peak\Bedrock\Http\Request\HandlerResolver;
use \Peak\Bedrock\Http\Request\Route;
use \Peak\Collection\PropertiesBag;
use \Psr\Container\ContainerInterface;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\UriInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Server\MiddlewareInterface;


require_once FIXTURES_PATH . '/application/HandlerA.php';
require_once FIXTURES_PATH . '/application/ResponseA.php';

/**
 * Class ApplicationTest
 */
class ApplicationTest extends TestCase
{
    /**
     * Test class instantiation
     */
    public function testGeneral()
    {
        $kernel = $this->createMock(Kernel::class);
        $handlerResolver = $this->createMock(HandlerResolver::class);

        $app = new Application($kernel, $handlerResolver, new PropertiesBag([
            'version' => '1.1'
        ]));

        $this->assertTrue($app->getProp('version') === '1.1');
        $this->assertInstanceOf(ContainerInterface::class, $app->getContainer());
        $this->assertInstanceOf(Kernel::class, $app->getKernel());
        $this->assertInstanceOf(HandlerResolver::class, $app->getHandlerResolver());

        $this->assertTrue($app->getProp('name') === null);
        $app->getProps()->set('name', 'Myapp');
        $this->assertTrue($app->getProp('name') === 'Myapp');
    }

    public function testHandleRequestWithAdd()
    {
        // app kernel
        $kernel = $this->createMock(Kernel::class);
        // request
        $request = $this->createMock(ServerRequestInterface::class);
        // request handler
        $handlerA = $this->createMock(RequestHandlerInterface::class);
        // handler resolver
        $handlerResolver = $this->createMock(HandlerResolver::class);
        $handlerResolver->expects($this->exactly(1))
            ->method('resolve')
            ->will($this->returnValue($handlerA));

        $app = new Application($kernel, $handlerResolver);
        $app->stack($handlerA);
        $this->assertInstanceOf(ResponseInterface::class, $app->handle($request));
    }

    public function testHandleRequestWithSet()
    {
        // app kernel
        $kernel = $this->createMock(Kernel::class);
        // request
        $request = $this->createMock(ServerRequestInterface::class);
        // request handler
        $handlerA = $this->createMock(RequestHandlerInterface::class);
        // handler resolver
        $handlerResolver = $this->createMock(HandlerResolver::class);
        $handlerResolver->expects($this->exactly(1))
            ->method('resolve')
            ->will($this->returnValue($handlerA));

        $app = new Application($kernel, $handlerResolver);
        $app->set($handlerA);
        $this->assertInstanceOf(ResponseInterface::class, $app->handle($request));
    }

    /**
     * @expectedException Peak\Bedrock\Http\Exception\EmptyStackException
     */
    public function testEmptyStackRequest()
    {
        // app kernel
        $kernel = $this->createMock(Kernel::class);
        // request
        $request = $this->createMock(ServerRequestInterface::class);
        // request handler
        $handlerA = $this->createMock(RequestHandlerInterface::class);
        // handler resolver
        $handlerResolver = $this->createMock(HandlerResolver::class);

        $app = new Application($kernel, $handlerResolver);
        $returnedResponse = $app->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $returnedResponse);
    }

    /**
     * @expectedException Peak\Bedrock\Http\Exception\EmptyStackException
     */
    public function testEmptyStackRequestWithReset()
    {
        // app kernel
        $kernel = $this->createMock(Kernel::class);
        // request
        $request = $this->createMock(ServerRequestInterface::class);
        // request handler
        $handlerA = $this->createMock(RequestHandlerInterface::class);
        // handler resolver
        $handlerResolver = $this->createMock(HandlerResolver::class);

        $app = new Application($kernel, $handlerResolver);

        $app->set($handlerA);
        $app->reset();

        $this->assertInstanceOf(ResponseInterface::class, $app->handle($request));
    }

    public function testHandleRequestWithRealHandler()
    {
        $kernel = $this->createMock(Kernel::class);
        $request = $this->createMock(ServerRequestInterface::class);

        $handlerA = new HandlerA();

        $handlerResolver = $this->createMock(HandlerResolver::class);
        $handlerResolver->expects($this->exactly(1))
            ->method('resolve')
            ->will($this->returnValue($handlerA));

        $app = new Application($kernel, $handlerResolver);
        $app->stack([$handlerA]);
        $this->assertInstanceOf(ResponseA::class, $app->handle($request));
    }


    public function testHandleWithRoutes()
    {
        $kernel = $this->createMock(Kernel::class);

        $handlerA = new HandlerA();

        $handlerResolver = $this->createMock(HandlerResolver::class);
        $handlerResolver->expects($this->exactly(1))
            ->method('resolve')
            ->will($this->returnValue($handlerA));

        $app = new Application($kernel, $handlerResolver);


        $app->get('/mypath', $handlerA);
        $app->post('/mypath', $handlerA);
        $app->put('/mypath', $handlerA);
        $app->patch('/mypath', $handlerA);
        $app->delete('/mypath', $handlerA);

        $app->all('/mypath', $handlerA);$request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->once()))
            ->method('getPath')
            ->will($this->returnValue('/mypath'));

        $request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($uri));

        $result = $app->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testBootstrap()
    {
        $app = new Application(
            $this->createMock(Kernel::class),
            $this->createMock(HandlerResolver::class)
        );

        $_GET = [];
        $app->bootstrap([
            function() {
                $_GET['test'] = 'foo';
            }
        ]);

        $this->assertTrue(isset($_GET['test']));
        $this->assertTrue($_GET['test'] === 'foo');
        $_GET = [];
    }

    public function testCreateRoute()
    {
        $app = new Application(
            $this->createMock(Kernel::class),
            $this->createMock(HandlerResolver::class)
        );

        $route = $app->createRoute('GET', '/', $this->createMock(\Peak\Blueprint\Http\Stack::class));
        $this->assertInstanceOf(Route::class, $route);

        $route = $app->createRoute('GET', '/', $this->createMock(MiddlewareInterface::class));
        $this->assertInstanceOf(Route::class, $route);
    }
}
