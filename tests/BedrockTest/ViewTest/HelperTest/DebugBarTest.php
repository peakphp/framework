<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View;
use Peak\Bedrock\View\Helper\DebugBar;

class DebugBarTest extends TestCase
{
    /**
     * Test instantiate
     */
    function testInstantiate()
    {
        $debugbar = new DebugBar(new View());
    }

    /**
     * Test container exception
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testRender()
    {
        $app = dummyApp();
        $debugbar = new DebugBar(new View());

        $content = $debugbar->render();

        $this->assertTrue(!empty($content));
    }

}