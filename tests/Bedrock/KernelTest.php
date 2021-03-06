<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Kernel;
use \Peak\Di\Container;
use \Psr\Container\ContainerInterface;

/**
 * Class KernelTest
 */
class KernelTest extends TestCase
{
    public function testVersion()
    {
        $this->assertTrue(!empty(Kernel::VERSION));
    }

    public function testInstantiation()
    {
        $container = $this->createMock(Container::class);
        $kernel = new Kernel('dev', $container);

        $this->assertTrue($kernel->getEnv() === 'dev');
        $this->assertInstanceOf(ContainerInterface::class, $kernel->getContainer());
    }
}
