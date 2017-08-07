<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Cache;
use Peak\Bedrock\View;

class CacheTest extends TestCase
{
    protected $cache_path = FIXTURES_PATH.'/cache/views';
    /**
     * Test code
     */
    function testBasics()
    {
        $view = new View();
        $view->engine('Layouts');
        $cache = new Cache($view, $this->cache_path);

        $cache->disable();
        $this->assertFalse($cache->isEnabled());
        $this->assertFalse($cache->isValid());

        $cache->enable(1);
        $this->assertTrue($cache->isEnabled());
    }

    /**
     * Test Validation
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testValidation()
    {
        $app = dummyApp();
        $view = $app->container()->get(View::class);
        $view->engine('Layouts');
        $cache = new Cache($view, $this->cache_path);

        $cache->enable(2);
        $this->assertTrue($cache->isEnabled());
        //$this->assertTrue($cache->isValid('test'));
        //sleep(2);
        //$this->assertTrue($cache->isValid('test'));
    }
}