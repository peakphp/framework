<?php

use \PHPUnit\Framework\TestCase;

use Peak\View\View;
use Peak\View\Presentation;

class ViewTest extends TestCase
{
    public function testCreate()
    {
        $view = new View(null, $this->createMock(Presentation::class));
        $this->assertTrue(is_array($view->getVars()));
        $this->assertTrue(empty($view->getVars()));
        $this->assertTrue($view->getPresentation() instanceof Presentation);
    }
}
