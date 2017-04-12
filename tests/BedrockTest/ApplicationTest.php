<?php
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * test application container
     */
    function testApplicationContainer()
    {
        \Peak\Bedrock\Application::setContainer(new \Peak\Di\Container);

        $this->assertTrue(\Peak\Bedrock\Application::container() instanceof \Peak\Di\ContainerInterface);
    }

    /**
     * test application creation 
     */
    function testApplicationCreation()
    {
        $container = new \Peak\Di\Container;

        $app = new \Peak\Bedrock\Application($container, [
            'env'  => 'dev',
            'conf' => 'config.php',
            'path' => [
                'public' => __DIR__,
                'app'    => __DIR__.'/../fixtures/app/',
            ]
        ]);

        $this->assertTrue($app instanceof \Peak\Bedrock\Application);
    }
    
}