<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Cli\Application;
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


class CliApplicationTest extends TestCase
{
    protected function createApp($kernel = null, $props = null)
    {
        return new Application(
            $kernel ?? $this->createMock(Kernel::class),
            $props ?? null
        );
    }

    /**
     * Test class instantiation
     */
    public function testGeneral()
    {
        $app = $this->createApp(null, new PropertiesBag([
            'version' => '1.1'
        ]));

        $this->assertTrue($app->getProp('version') === '1.1');
        $this->assertInstanceOf(ContainerInterface::class, $app->getContainer());
        $this->assertInstanceOf(Kernel::class, $app->getKernel());

        $this->assertTrue($app->getProp('name') === null);
        $app->getProps()->set('name', 'Myapp');
        $this->assertTrue($app->getProp('name') === 'Myapp');
        $this->assertInstanceOf(\Symfony\Component\Console\Application::class, $app->console());
    }

}
