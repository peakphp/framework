<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Controller\ChildActionController;
use Peak\Bedrock\Controller\ParentController;
use Peak\Bedrock\View;
use Peak\Common\Collection;
use Peak\Routing\RouteBuilder;

class ChildApplicationControllerTest extends TestCase
{

    protected $app;

    /**
     * Test load controller
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testLoadController()
    {
        $app = dummyApp();
        $parent = Application::create(TestParentController::class, [
            '' // empty namespace prefix
        ]);
        $child = Application::create(TestChildAction::class, [], [
            ParentController::class => $parent
        ]);


        $parent->setRoute(RouteBuilder::get('parent/testchild'));
        $parent->dispatch();

        $this->assertTrue($parent->preaction);
        $this->assertTrue($parent->preaction);
        $this->assertTrue($parent->child instanceof TestChildAction);
        $this->assertTrue($parent->child->processed);
        $this->assertTrue($parent->child->coll instanceof Collection);
    }


}

class TestParentController extends ParentController
{
    public $preaction = false;
    public $postaction = false;

    public function preAction()
    {
        $this->preaction = true;
    }

    public function postAction()
    {
        $this->postaction = true;
    }
}

class TestChildAction extends ChildActionController
{
    public $processed = false;

    public function process(Collection $collection)
    {
        $this->processed = true;
        $this->coll = $collection;
    }
}