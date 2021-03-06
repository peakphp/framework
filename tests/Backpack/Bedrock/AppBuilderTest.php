<?php

use \PHPUnit\Framework\TestCase;
//use \Peak\Backpack\Bedrock\HttpAppBuilder as AppBuilder;
use \Peak\Backpack\AppBuilder;
use \Peak\Blueprint\Bedrock\Application as ApplicationBlueprint;
use \Peak\Bedrock\Http\Application;
use \Peak\Bedrock\Kernel;
use \Peak\Http\Request\HandlerResolver;
use \Peak\Di\Container;
use \Psr\Container\ContainerInterface;

require_once FIXTURES_PATH . '/application/CustomKernel.php';

class AppBuilderTest extends TestCase
{
    public function testDefault()
    {
        $app = (new AppBuilder())
            ->build();

        $this->assertInstanceOf(Application::class, $app);
        $this->assertInstanceOf(ApplicationBlueprint::class, $app);
        $this->assertInstanceOf(ContainerInterface::class, $app->getContainer());
        $this->assertInstanceOf(Container::class, $app->getContainer());
        $this->assertNull($app->getProps());
    }

    public function testSetEnv()
    {
        $app = (new AppBuilder())
            ->setEnv('staging')
            ->build();

        $this->assertTrue($app->getKernel()->getEnv() === 'staging');
    }

    public function testSetHandlerResolver()
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject */
        $handlerResolver = $this->createMock(HandlerResolver::class);
        $handlerResolver->test = 'foobar';

        $app = (new AppBuilder())
            ->setHandlerResolver($handlerResolver)
            ->build();

        $this->assertTrue($app->getHandlerResolver()->test === 'foobar');
    }

    public function testSetContainer()
    {
        $container = new \Peak\Di\Container();
        $container->set(new \Peak\Collection\Collection(['foo' => 'bar']));
        $app = (new AppBuilder())
            ->setContainer($container)
            ->build();

        $appContainer = $app->getContainer();
        $this->assertInstanceOf(\Peak\Collection\Collection::class, $appContainer->get(\Peak\Collection\Collection::class));
        $this->assertTrue($appContainer->get(\Peak\Collection\Collection::class)['foo'] === 'bar');
    }

    public function testSetAppName()
    {
        $app = (new AppBuilder())
            ->setAppClass(Application::class)
            ->build();

        $this->assertInstanceOf(Application::class, $app);
    }

    public function testSetKernel()
    {
        $kernel = $this->createMock(Kernel::class);
        $kernel->expects(($this->once()))
            ->method('getEnv')
            ->will($this->returnValue('foobar'));

        $app = (new AppBuilder())
            ->setKernel($kernel)
            ->build();

        $this->assertTrue($app->getKernel()->getEnv() === 'foobar');
    }

    public function testSetKernelClass()
    {
        $app = (new AppBuilder())
            ->setKernelClass(CustomKernel::class)
            ->build();

        $this->assertInstanceOf(CustomKernel::class, $app->getKernel());
    }

    public function testSetProps()
    {
        $app = (new AppBuilder())
            ->setProps(new \Peak\Collection\PropertiesBag(['test' => 'foobar']))
            ->build();

        $this->assertTrue($app->hasProp('test'));
    }

    public function testSetPropsArray()
    {
        $app = (new AppBuilder())
            ->setProps(['test' => 'foobar'])
            ->build();

        $this->assertTrue($app->hasProp('test'));
    }

    public function testSetPropsException()
    {
        $this->expectException(Exception::class);
        $app = (new AppBuilder())
            ->setProps('test')
            ->build();
    }

    public function testExecuteAfterBuild()
    {
        $app = (new AppBuilder())
            ->executeAfterBuild(function($app) {
                $app->test = 'foobar';
            })
            ->build();

        $this->assertTrue($app->test === 'foobar');
    }

    public function testTriggerKernelError1()
    {
        $this->expectException(\PHPUnit\Framework\Error\Error::class);
        $kernel = $this->createMock(Kernel::class);

        $app = (new AppBuilder())
            ->setEnv('barfoo')
            ->setKernel($kernel)
            ->build();
    }

    public function testTriggerKernelError2()
    {
        $this->expectException(\PHPUnit\Framework\Error\Error::class);
        $kernel = $this->createMock(Kernel::class);
        $container = $this->createMock(Container::class);
        $app = (new AppBuilder())
            ->setContainer($container)
            ->setKernel($kernel)
            ->build();
    }

    public function testTriggerKernelError3()
    {
        $this->expectException(\PHPUnit\Framework\Error\Error::class);
        $kernel = $this->createMock(Kernel::class);
        $container = $this->createMock(Container::class);
        $app = (new AppBuilder())
            ->setKernelClass(Kernel::class)
            ->setKernel($kernel)
            ->build();
    }

    public function testAddToContainerAfterBuild()
    {
        $app = (new AppBuilder())
            ->setContainer(new Container())
            ->addToContainerAfterBuild()
            ->build();

        $this->assertTrue($app->getContainer()->has(get_class($app)));

        $app = (new AppBuilder())
            ->setContainer(new Container())
            ->build();

        $this->assertFalse($app->getContainer()->has(get_class($app)));
    }

    public function testAddToContainerAfterBuildException()
    {
        $this->expectException(\Exception::class);
        $app = (new AppBuilder())
            ->setContainer($this->createMock(ContainerInterface::class))
            ->addToContainerAfterBuild()
            ->build();
    }
}
