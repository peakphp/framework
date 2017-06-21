<?php
use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Config;
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
     * Test container exception
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testApplicationContainerException()
    {
        try {
            $test = Application::get('test');
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        $this->assertTrue(isset($error));
        $this->assertTrue($error === 'Peak\Bedrock\Application has no container');
    }

    /**
     * Test container exception
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testApplicationContainerException2()
    {
        Application::setContainer(new Container);
        try {
            $test = Application::get('test');
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        $this->assertTrue(isset($error));
        $this->assertTrue($error === 'Peak\Bedrock\Application container does not have test');
    }

    /**
     * test application creation 
     */
    function testApplicationCreation()
    {
        $container = new Container;

        $app = new Application($container, [
            'env'  => 'dev',
            'conf' => FIXTURES_PATH.'/app/config.php',
            'path' => [
                'public' => __DIR__,
                'app'    => FIXTURES_PATH.'/app/',
            ]
        ]);

        $this->assertTrue($app instanceof Application);
        $this->assertTrue(Application::conf() instanceof Config);

        Application::conf('temp', 'value1');
        $this->assertTrue(Application::conf('temp') === 'value1');
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