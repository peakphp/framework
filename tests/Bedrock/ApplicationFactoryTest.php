<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Application;
use \Peak\Bedrock\ApplicationFactory;
use \Peak\Blueprint\Resolvable;
use \Psr\Container\ContainerInterface;

/**
 * Class ApplicationFactoryTest
 */
class ApplicationFactoryTest extends TestCase
{
    public function testCreate()
    {
        $appFactory = new ApplicationFactory();

        $app = $appFactory->create(
            'dev',
            $this->createMock(ContainerInterface::class),
            $this->createMock(Resolvable::class),
            '2.0'
        );

        $this->assertInstanceOf(Application::class, $app);
        $this->assertTrue($app->getVersion() === '2.0');
    }
}
