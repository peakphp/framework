<?php

use \PHPUnit\Framework\TestCase;

use Peak\View\View;
use Peak\View\Presentation;

class ViewTest extends TestCase
{
    public function testBasic()
    {
        $view = new View(null, $this->createMock(Presentation::class));
        $this->assertTrue(is_array($view->getVars()));
        $this->assertTrue(empty($view->getVars()));
        $this->assertTrue($view->getPresentation() instanceof Presentation);
    }

    public function testGetVars()
    {
        $view = new View(['test' => 'foobar'], $this->createMock(Presentation::class));
        $vars = $view->getVars();
        $this->assertTrue(isset($vars['test']));
        $this->assertTrue($vars['test'] === 'foobar');
        $this->assertFalse(isset($vars['test2']));
    }

    public function testVarGetter()
    {
        $view = new View(['test' => 'foobar'], $this->createMock(Presentation::class));
        $this->assertTrue($view->test === 'foobar');
    }

    public function testVarIsset()
    {
        $view = new View(['test' => 'foobar'], $this->createMock(Presentation::class));
        $this->assertTrue(isset($view->test));
        $this->assertFalse(isset($view->test2));
    }
}
