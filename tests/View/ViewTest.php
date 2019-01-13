<?php

use \PHPUnit\Framework\TestCase;

use Peak\View\View;
use Peak\View\Presentation;

require_once FIXTURES_PATH.'/view/helpers/ViewHelperA.php';

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

    /**
     * @expectedException \Exception
     */
    public function testVarGetterException()
    {
        $view = new View([], $this->createMock(Presentation::class));
        $view->test;
    }

    public function testVarIsset()
    {
        $view = new View(['test' => 'foobar'], $this->createMock(Presentation::class));
        $this->assertTrue(isset($view->test));
        $this->assertFalse(isset($view->test2));
    }

    public function testAddMacro()
    {
        $view = new View(['name' => 'foobar'], $this->createMock(Presentation::class));
        $view->addMacro('macro1', function() {
            return $this->name;
        });
        $this->assertTrue($view->hasMacro('macro1'));
        $this->assertFalse($view->hasMacro('macro2'));
        $this->assertTrue($view->macro1() === 'foobar');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMacroHelperException()
    {
        $view = new View([], $this->createMock(Presentation::class));
        $view->macro();
    }

    public function testHelper()
    {
        $view = new View([], $this->createMock(Presentation::class));
        $view->setHelpers([
            'myHelper' => new ViewHelperA(),
        ]);

        $this->assertTrue($view->myHelper('bob') === 'Hello bob!');
    }

    public function testRender()
    {
        $view = new View(
            ['name' => 'foo'],
            new Presentation(['/layout.php' => ['/profile.php']], FIXTURES_PATH.'/view/scripts')
        );
        $content = $view->render();
        $this->assertTrue($content === '<div class="content"><h1>Profile of foo</h1></div>');
    }

    /**
     * @expectedException \Peak\View\Exception\FileNotFoundException
     */
    public function testRenderFail()
    {
        $view = new View([],
            new Presentation(['/layout.php' => ['/unknown.php']], FIXTURES_PATH . '/view/scripts')
        );
        $view->render();
    }
}
