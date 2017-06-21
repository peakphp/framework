<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View;
use Peak\Bedrock\Application;

/**
 * @package    Peak\Bedrock\View
 */
class ViewTest extends TestCase
{
	/**
	 * init view
	 */ 
	function setUp()
	{
		$this->peakview = new View();
	}
	
	/**
	 * unset view
	 */
	function tearDown()
	{
		unset($this->peakview);
	}
	
	/**
	 * Create instance test
	 */
	function testCreateInstance()
	{
		$view = new View();
		$this->assertInstanceOf('Peak\Bedrock\View', $view); 
	}
	
	/**
	 * Create instance test with array
	 */
	function testCreateInstanceWithArray()
	{
		$view = new View(array('test' => 'value'));
		$this->assertInstanceOf('Peak\Bedrock\View', $view);
		
		$vars = $view->getVars();		
		$this->assertArrayHasKey('test', $vars);
	}
	
	/**
	 * Test vars manipulation (get,set,isset,unset)
	 */
	function testManipulateVars()
	{
		//__isset
		$this->assertFalse(isset($this->peakview->unknowvar));
		
		//__set, __isset, __get
		$this->peakview->test = 'value';		
		$this->assertTrue(isset($this->peakview->test));
		$this->assertTrue($this->peakview->test === 'value');
		
		//passed by ref __get
		$this->peakview->test2 = array('key1' => 'value1');
		$this->assertTrue($this->peakview->test2['key1'] === 'value1');
		$this->peakview->test2['key1'] = 'novalue';
		$this->assertTrue($this->peakview->test2['key1'] === 'novalue');
				
		//__unset, __isset
		unset($this->peakview->test);
		$this->assertFalse(isset($this->peakview->test));
		
		//set
		$this->peakview->set('test', 'value');		
		$this->assertTrue(isset($this->peakview->test));
		$this->assertTrue($this->peakview->test === 'value');
		unset($this->peakview->test);
		
	}
	
	/**
	 * Test count var
	 */
	function testCountVars()
	{
		$this->assertTrue($this->peakview->countVars() == 0);
		
		$this->peakview->test = 'value';
		$this->assertTrue($this->peakview->countVars() == 1);
		
		unset($this->peakview->test);
	}
	
	/**
	 * Test get all view vars
	 */
	function testGetVars()
	{
		$this->assertTrue(is_array($this->peakview->getVars()));
	}
	
	/**
	 * Test reset(empty) all view vars
	 */
	function testResetVars()
	{
		//set a variable
		$this->peakview->test = 'value';
		
		//reset vars
		$this->peakview->resetVars();
		
		//check is vars array is empty
		$vars = $this->peakview->getVars();
		$this->assertTrue(empty($vars));
	}

	/**
	 * Test method setVars()
	 */
	function testSetVars()
	{
		$this->assertTrue($this->peakview->countVars() == 0);

		$this->peakview->setVars(array('test' => 'test1', 'name' => 'john'));

		$this->assertTrue($this->peakview->countVars() == 2);
	}

	/**
	 * Test addVars()
	 */
	function testAddVars()
	{
		$this->assertTrue($this->peakview->countVars() == 0);

		$this->peakview->addVars(array('test' => 'test1', 'name' => 'john'));

		$this->assertTrue($this->peakview->countVars() == 2);

		$this->peakview->addVars(array('test2' => 'test1', 'name' => 'john'));

		$this->assertTrue($this->peakview->countVars() == 3);
	}

	/**
	 * Test engine() stuff
	 */
	function testSetEngine()
	{
		$name = $this->peakview->getEngineName();

		$this->assertTrue($name === null);

		\Peak\Bedrock\Application::setContainer(new \Peak\Di\Container);
		//\Peak\Bedrock\Application::instantiate('\Peak\Bedrock\View');

		$this->peakview->engine('Layouts');

		$name = $this->peakview->getEngineName();

		$this->assertTrue($name === 'Layouts');
	}
	
	/**
	 * @expectedException Exception
	 */
	function testRenderException()
	{
		//try render a script file when no rendering engine have been set before
		try {
			$this->peakview->render('test','test');
		}
		catch (InvalidArgumentException $expected) {
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
	}

    /**
     * Test engine exception
     */
	function testEngineNotFoundException()
    {
        try {
            $this->peakview->engine('Unknown');
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }
	
	/**
	 * Test methods enableRender(), disableRender() and canRender()
	 */
	function testOnOffRender()
	{
		$view = new Peak\Bedrock\View();
		
		$this->assertTrue($view->canRender());

		$view->enableRender();
		
		$this->assertTrue($view->canRender());
		
		$view->disableRender();
		
		$this->assertFalse($view->canRender());
	}

    /**
     * Test Helper
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
	function testHelper()
    {
        $container = new Peak\Di\Container;

        $app = new Application($container, [
            'env'  => 'dev',
            'conf' => FIXTURES_PATH.'/app/config.php',
            'path' => [
                'public' => __DIR__,
                'app'    => FIXTURES_PATH.'/app/',
            ]
        ]);

        $view = Application::get(View::class);
        $helper = $view->text();
        $this->assertTrue($helper instanceof \Peak\Bedrock\View\Helper\Text);

        $helper = $view->helper('text');
        $this->assertTrue($helper instanceof \Peak\Bedrock\View\Helper\Text);

        // test exception
        try {
            $helper = $view->unknown();
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }
}