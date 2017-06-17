<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Controller\ActionController;
use Peak\Bedrock\View;
use Peak\Routing\Route;

class ApplicationControllerTest extends TestCase
{

    /**
     * Test load controller
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testLoadController()
    {
        $app = dummyApp();
        $controller = Application::instantiate(TestController::class);
        $this->assertTrue($controller->view instanceof View);
        $this->assertTrue($controller->getTitle() === 'Test');
        $this->assertFalse($controller->isAction('index'));
    }

    /**
     * Test controller route
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testControllerRoute()
    {
        $app = dummyApp();
        $controller = Application::instantiate(TestController::class);

        $route = new Route();
        $route->action = 'myaction';

        $controller->setRoute($route);

        $this->assertTrue($controller->action === '_myaction');

        $route = new Route();
        $route->action = '';

        $controller->setRoute($route);

        $this->assertTrue($controller->action === '_index');
    }

    /**
     * Test controller dispatch
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testControllerDispatch()
    {
        $app = dummyApp();
        $controller = Application::instantiate(TestController::class);

        $route = new Route();
        $route->action = 'index';

        $controller->setRoute($route);
        $controller->dispatch();

        $this->assertTrue($controller->preaction);
        $this->assertTrue($controller->postaction);
        $this->assertTrue($controller->view->foo === 'bar');
    }

    /**
     * Test controller dispatch action exception
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testControllerDispatchException()
    {
        $app = dummyApp();
        $controller = Application::instantiate(TestController::class);

        $route = new Route();
        $route->action = 'unknown_action';

        $controller->setRoute($route);

        try {
            $controller->dispatch();
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }



}

class TestController extends ActionController
{
    public $preaction = false;
    public $postaction = false;

    public function preAction()
    {
        $this->preaction = true;
    }

    public function _index()
    {
        $this->view->foo = 'bar';
    }

    public function postAction()
    {
        $this->postaction = true;
    }
}