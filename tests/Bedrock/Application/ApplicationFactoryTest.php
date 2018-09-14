<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Application\Application;
use \Peak\Bedrock\Application\ApplicationFactory;
use \Peak\Blueprint\Bedrock\Kernel;
use \Peak\Blueprint\Common\ResourceResolver;
use \Peak\Collection\PropertiesBag;
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
            $this->createMock(ResourceResolver::class),
            $this->createMock(PropertiesBag::class)
        );

        $this->assertInstanceOf(Application::class, $app);
        $this->assertInstanceOf(PropertiesBag::class, $app->getProps());
    }

    public function testCreateFromKernel()
    {
        $appFactory = new ApplicationFactory();

        $app = $appFactory->createFromKernel(
            $this->createMock(Kernel::class),
            $this->createMock(ResourceResolver::class),
            $this->createMock(PropertiesBag::class)
        );

        $this->assertInstanceOf(Application::class, $app);
        $this->assertInstanceOf(PropertiesBag::class, $app->getProps());
    }
}
