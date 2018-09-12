<?php

use PHPUnit\Framework\TestCase;

use Peak\DebugBar\DebugBar;

class DebugBarTest extends TestCase
{
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