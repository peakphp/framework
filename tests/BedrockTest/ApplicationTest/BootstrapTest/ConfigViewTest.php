<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Bootstrap\ConfigView;
use Peak\Bedrock\View;
use Peak\Bedrock\View\Render\Layouts;

class ConfigViewTest extends TestCase
{
    /**
     * Test bootstrap class
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testBootstrap()
    {
        $app = dummyApp();
        Application::instantiate(ConfigView::class);
        $view = Application::get(View::class);
        $this->assertTrue($view->engine() instanceof Layouts);
        $this->assertTrue($view->var1 === 'foo');
        $this->assertTrue($view->var2 === 'bar');
    }

    /**
     * Test bootstrap class with empty view
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testBootstrapEmpty()
    {
        $app = dummyApp();
        $conf = Application::conf();
        unset($conf->view);
        Application::instantiate(ConfigView::class);
    }
}