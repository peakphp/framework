<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Application\Application;
use \Peak\Bedrock\Application\BlackMagic;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;

require_once FIXTURES_PATH . '/application/HandlerA.php';
require_once FIXTURES_PATH . '/application/MiddlewareA.php';

/**
 * Class BlackMagicTest
 */
class BlackMagicTest extends TestCase
{
    public function testCreateApp()
    {
        $app = BlackMagic::createApp();
        $this->assertInstanceOf(Application::class, $app);
        $this->assertTrue($app->getKernel()->getEnv() === 'dev');

        $app = BlackMagic::createApp('test', new \Peak\Collection\PropertiesBag(['version' => '2.0']));
        $this->assertInstanceOf(Application::class, $app);
        $this->assertTrue($app->getKernel()->getEnv() === 'test');
        $this->assertTrue($app->getProp('version') === '2.0');
    }

    public function testCreateAppStack()
    {
        $app = BlackMagic::createApp();
        $stack = BlackMagic::createAppStack($app, [
            MiddlewareA::class,
            HandlerA::class,
        ]);
        $this->assertInstanceOf(\Peak\Blueprint\Http\Stack::class, $stack);
    }

    /**
     * @runInSeparateProcess
     */
    public function testEmit()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue([]));
        $response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->will($this->returnValue(200));
        $response->expects($this->exactly(2))
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $this->assertTrue(BlackMagic::emit($response));
    }

    /**
     * @runInSeparateProcess
     */
    public function testRun()
    {
        $app = BlackMagic::createApp();

        $app->add(BlackMagic::createAppStack($app, [
            MiddlewareA::class,
            HandlerA::class,
        ]));

        $this->assertTrue(BlackMagic::run($app, $this->createMock(ServerRequestInterface::class)));
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunThis()
    {
        $app = BlackMagic::createApp();

        $this->assertTrue(
            BlackMagic::runThis(
                $app,
                [
                    MiddlewareA::class,
                    HandlerA::class,
                ],
                $this->createMock(ServerRequestInterface::class)
            )
        );
    }

}
