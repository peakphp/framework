<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\Application;
use Peak\Bedrock\Controller\ActionController;
use Peak\Bedrock\View;
use Peak\Routing\Route;
use Peak\Routing\RouteBuilder;

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
        $controller = Application::create(TestController::class);
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
        $controller = Application::create(TestController::class);

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
        $controller = Application::create(TestController::class);

        $route = new Route();
        $route->action = 'index';

        $controller->setRoute($route);
        $controller->dispatch();

        $this->assertTrue($controller->preaction);
        $this->assertTrue($controller->postaction);
        $this->assertTrue($controller->file === 'test.index.php');
        $this->assertTrue($controller->view->foo === 'bar');
    }

    /**
     * Test controller dispatch
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testControllerDispatchRedirectAction()
    {
        $app = dummyApp();
        $controller = Application::create(TestController::class);
        Application::kernel()->front->controller = $controller;

        $route = new Route();
        $route->action = 'redirect';

        $controller->setRoute($route);
        $controller->dispatch();

        $this->assertTrue($controller->preaction);
        $this->assertTrue($controller->postaction);
        $this->assertTrue($controller->redirected);
        $this->assertTrue($controller->file === 'test.redirected.php');
    }


    /**
     * Test controller dispatch
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testControllerCaching()
    {
        $app = dummyApp();

        $controller = Application::create(TestController::class);
        $route = RouteBuilder::get('test/testingcache');

        $controller->view->engine('Layouts');

        $controller->setRoute($route);
        $controller->dispatch();

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
        $controller = Application::create(TestController::class);

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

    /**
     * Test controller dispatch with action with params
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testControllerDispatchActionParams()
    {
        $app = dummyApp();

        $controller = Application::create(TestController::class);
        $route = RouteBuilder::get('test/actionwithparams/id/13/sort/joindate');

        $controller->setRoute($route);
        $controller->dispatch();

        $this->assertTrue($controller->id == 13);
        $this->assertTrue($controller->sort === 'joindate');
        $this->assertTrue($controller->file === 'test.actionwithparams.php');
    }


}

class TestController extends ActionController
{
    public $preaction = false;
    public $postaction = false;
    public $redirected = true;
    public $id = null;
    public $sort = null;

    public function preAction()
    {
        $this->preaction = true;
    }

    public function _index()
    {
        $this->view->foo = 'bar';
    }

    public function _actionWithParams($id, $sort = 'name')
    {
        $this->id = $id;
        $this->sort = $sort;
    }

    public function _testingCache()
    {
        $this->view->cache()->genCacheId('test', 'testingcache');
        $this->view->cache()->enable(1);
        $cachevalid = $this->view->cache()->isValid();
        if(!$cachevalid) {
            // do stuff
        }
    }

    public function _redirect()
    {
        $this->redirectAction('redirected');
    }

    public function _redirected()
    {
        $this->redirected = true;
    }

    public function postAction()
    {
        $this->postaction = true;
    }
}