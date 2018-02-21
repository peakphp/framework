<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View;
use Peak\Bedrock\View\Block;

class BlockTest extends TestCase
{
    protected $block = FIXTURES_PATH.'/view/block.php';

    /**
     * Test code
     */
    function testBasics()
    {
        $view = new View();
        $block = new Block($view, $this->block, ['foo' => 'bar']);

        $expected = 'My name is bar';

        $this->assertTrue($block->render() === $expected);
    }
}
