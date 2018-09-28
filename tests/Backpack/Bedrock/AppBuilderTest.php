<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Backpack\Bedrock\AppBuilder;
use \Peak\Blueprint\Bedrock\Application as ApplicationBlueprint;
use \Peak\Bedrock\Application\Application;
use \Psr\Container\ContainerInterface;

class AppBuilderTest extends TestCase
{
    public function testDefault()
    {
        $app = (new AppBuilder())
            ->build();

        $this->assertInstanceOf(Application::class, $app);
        $this->assertInstanceOf(ApplicationBlueprint::class, $app);
        $this->assertInstanceOf(ContainerInterface::class, $app->getContainer());
        $this->assertNull($app->getProps());
    }
}
