<?php
use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Di\Container;
use Peak\Common\Collection;

class ApplicationTest extends TestCase
{

    protected $app;

    /**
     * test application container
     */
    function testApplicationContainer()
    {
        Application::setContainer(new Container);

        $this->assertTrue(Application::container() instanceof \Peak\Di\ContainerInterface);
    }

    /**
     * test application creation 
     */
    function testApplicationCreation()
    {
        $container = new Container;

        $app = new Application($container, [
            'env'  => 'dev',
            'conf' => 'config.php',
            'path' => [
                'public' => __DIR__,
                'app'    => __DIR__.'/../fixtures/app/',
            ]
        ]);

        $this->assertTrue($app instanceof Application);

        $this->app = $app;
    }

    /**
     * test application container static accessor 
     */
    function testApplicationContainerStaticMethods()
    {
        Application::container()->add(new Collection)
            ->addAlias('mycol', Collection::class);

        $this->assertTrue(Application::get('mycol') instanceof Collection);

        $this->assertTrue(Application::instantiate(Collection::class) instanceof Collection);
    }
    
}