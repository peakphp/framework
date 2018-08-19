<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Application\Application;
use \Peak\Bedrock\Application\ApplicationFactory;
use \Peak\Blueprint\Common\Resolvable;
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
