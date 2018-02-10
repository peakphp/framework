<?php

use PHPUnit\Framework\TestCase;

use Peak\DebugBar\DebugBar;

class DebugBarTest extends TestCase
{
    /**
     * Test instantiate
     */
    function testInstantiate()
    {
        $debugbar = new DebugBar();
    }

    /**
     * Test container exception
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testRender()
    {
        $debugbar = new DebugBar();
        $content = $debugbar->render();
        $this->assertTrue(!empty($content));
    }

}