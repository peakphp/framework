<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Application\Application;
use \Peak\Bedrock\Kernel;
use \Peak\Http\Request\HandlerResolver;
use \Peak\Http\Request\Route;
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
    protected function createApp($kernel = null, $handlerResolver = null, $props = null)
    {
        return new Application(
            $kernel ?? $this->createMock(Kernel::class),
            $handlerResolver ?? $this->createMock(HandlerResolver::class),
            $props ?? null
        );
    }

    /**
     * Test class instantiation
     */
    public function testGeneral()
    {
        $app = $this->createApp(null, null, new PropertiesBag([
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
        // request
        $request = $this->createMock(ServerRequestInterface::class);
        // request handler
        $handlerA = $this->createMock(RequestHandlerInterface::class);

        $app = $this->createApp();
        $app->stack($handlerA);
        $this->assertInstanceOf(ResponseInterface::class, $app->handle($request));
    }

    public function testHandleRequestWithSet()
    {
        // request
        $request = $this->createMock(ServerRequestInterface::class);
        // request handler
        $handlerA = $this->createMock(RequestHandlerInterface::class);

        $app = $this->createApp();
        $app->set($handlerA);
        $this->assertInstanceOf(ResponseInterface::class, $app->handle($request));
    }

    /**
     * @expectedException Peak\Http\Exception\EmptyStackException
     */
    public function testEmptyStackRequest()
    {
        // request
        $request = $this->createMock(ServerRequestInterface::class);

        $app = $this->createApp();
        $returnedResponse = $app->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $returnedResponse);
    }

    /**
     * @expectedException Peak\Http\Exception\EmptyStackException
     */
    public function testEmptyStackRequestWithReset()
    {
        // request
        $request = $this->createMock(ServerRequestInterface::class);
        // request handler
        $handlerA = $this->createMock(RequestHandlerInterface::class);

        $app = $this->createApp();

        $app->set($handlerA);
        $app->reset();

        $this->assertInstanceOf(ResponseInterface::class, $app->handle($request));
    }

    public function testHandleRequestWithRealHandler()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $handlerA = new HandlerA();

        $app = $this->createApp();
        $app->stack([$handlerA]);
        $this->assertInstanceOf(ResponseA::class, $app->handle($request));
    }


    public function testHandleWithRoutes()
    {
        $handlerA = new HandlerA();

        $app = $this->createApp();


        $app->get('/mypath', $handlerA);
        $app->post('/mypath', $handlerA);
        $app->put('/mypath', $handlerA);
        $app->patch('/mypath', $handlerA);
        $app->delete('/mypath', $handlerA);
        $app->all('/mypath2', $handlerA);

        $request = $this->createMock(ServerRequestInterface::class);

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(($this->once()))
            ->method('getPath')
            ->will($this->returnValue('/mypath'));

        $request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue($uri));

        $request->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $result = $app->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testBootstrap()
    {
        $app = $this->createApp();

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
        $app = $this->createApp();

        $route = $app->createRoute('GET', '/', $this->createMock(\Peak\Blueprint\Http\Stack::class));
        $this->assertInstanceOf(Route::class, $route);

        $route = $app->createRoute('GET', '/', $this->createMock(MiddlewareInterface::class));
        $this->assertInstanceOf(Route::class, $route);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetPropOnNull()
    {
        $app = $this->createApp();

        $app->getProp('foo');
    }

    /**
     * @expectedException \Exception
     */
    public function testHasPropOnNull()
    {
        $app = $this->createApp();

        $app->hasProp('foo');
    }

    public function testStackRoute()
    {
        $app = $this->createApp();

        $app->stackRoute('post', '/', null);

        $handlers = $app->getHandlers();
        $this->assertTrue(count($handlers) == 1);
        $this->assertTrue($handlers[0] instanceof Route);
        $this->assertTrue($handlers[0]->getMethod() === 'POST');
    }

    public function testStackIfTrue()
    {
        $app = $this->createApp();
        $app->stackIfTrue(true, function(){});
        $handlers = $app->getHandlers();
        $this->assertTrue(count($handlers) == 1);

        $app = $this->createApp();
        $app->stackIfTrue(false, function(){});
        $handlers = $app->getHandlers();
        $this->assertTrue(count($handlers) == 0);
    }
}
