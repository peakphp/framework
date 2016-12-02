<?php
use PHPUnit\Framework\TestCase;

/**
 * @package    Peak\Router
 */
class RouterTest extends TestCase
{
	
	function setUp()
	{
		$this->peakrouter = new Peak\Router('');
	}

	function testCreateInstance()
	{
		$rt = new Peak\Router('');		
		$this->assertInstanceOf('Peak\Router', $rt); 
	}
	
	
	function testBaseUri()
	{
		//check $base_uri
		$this->assertTrue($this->peakrouter->base_uri === '/');
	}
	
	/**
     * @backupGlobals enabled
     */
	function testBasicGetRequestURI()
	{
		//fake $_SERVER['REQUEST_URI'];
		$_SERVER['REQUEST_URI'] = '/mycontroller/myaction/param1/param2';
		
		$this->peakrouter->getRequestURI();
		//print_r($this->peakrouter);
		
		//request uri , controller , action
		$this->assertTrue($this->peakrouter->request_uri === 'mycontroller/myaction/param1/param2');
		$this->assertTrue($this->peakrouter->controller === 'mycontroller');
		$this->assertTrue($this->peakrouter->action === 'myaction');
				
		//params
		$this->assertTrue(is_array($this->peakrouter->params));
		$this->assertTrue($this->peakrouter->params[0] === 'param1');
		$this->assertTrue($this->peakrouter->params[1] === 'param2');
		
		//params assoc
		$this->assertTrue(is_array($this->peakrouter->params_assoc));
		$this->assertArrayHasKey('param1', $this->peakrouter->params_assoc);
		
		//request
		$this->assertTrue(is_array($this->peakrouter->request));
		$this->assertTrue(count($this->peakrouter->request) == 4);		
	}
	
	function testResetRouter()
	{
		$this->peakrouter->reset();
		//print_r($this->peakrouter);
		
		//base uri , request uri , controller , action
		$this->assertTrue($this->peakrouter->base_uri === '/');
		$this->assertEmpty($this->peakrouter->request_uri);
		$this->assertEmpty($this->peakrouter->controller);
		$this->assertEmpty($this->peakrouter->action);
				
		//params
		$this->assertEmpty($this->peakrouter->params);
		
		//params assoc
		$this->assertEmpty($this->peakrouter->params_assoc);
		
		//request
		$this->assertEmpty($this->peakrouter->request);
	}
	
	/**
     * @backupGlobals enabled
     */
	function testBasicOldStyleGetRequestURI()
	{
		//fake $_SERVER['REQUEST_URI'];
		$_SERVER['REQUEST_URI'] = 'index.php?mycontroller=myaction&param1=param2';
		
		//fake $_GET
		$_GET = array('mycontroller' => 'myaction', 'param1' => 'param2');
		
		$this->peakrouter->getRequestURI();
		//print_r($this->peakrouter);
		
		//request uri , controller , action
		$this->assertTrue($this->peakrouter->request_uri === 'index.php?mycontroller=myaction&param1=param2');
		$this->assertTrue($this->peakrouter->controller === 'mycontroller');
		$this->assertTrue($this->peakrouter->action === 'myaction');
				
		//params
		$this->assertTrue(is_array($this->peakrouter->params));
		$this->assertTrue($this->peakrouter->params[0] === 'param1');
		$this->assertTrue($this->peakrouter->params[1] === 'param2');
		
		//params assoc
		$this->assertTrue(is_array($this->peakrouter->params_assoc));
		$this->assertArrayHasKey('param1', $this->peakrouter->params_assoc);
		
		//request
		$this->assertTrue(is_array($this->peakrouter->request));
		$this->assertTrue(count($this->peakrouter->request) == 4);
	}
	
	/**
	 * @expectedException Exception
	 */
	function testGetRequestURIException()
	{
		try {
			//fake $_SERVER['REQUEST_URI'];
		    $_SERVER['REQUEST_URI'] = '/mycontroller/index.php';	    
			$this->peakrouter->getRequestURI();
		}
		catch (InvalidArgumentException $expected) {
            return;
        }
        $this->fail('An expected exception has not been raised.');
        
        
        $this->peakrouter->reset();
        
        try {
			//fake $_SERVER['REQUEST_URI'];
		    $_SERVER['REQUEST_URI'] = '/index.php/mycontroller';	    
			$this->peakrouter->getRequestURI();
		}
		catch (InvalidArgumentException $expected) {
            return;
        }
        $this->fail('An expected exception has not been raised.');
        
        
	}
	
	function testSimpleRegex1()
	{
		//add regex the standard way
		$this->peakrouter->addRegex('custom_page', array('controller' => 'index', 'action' => 'news'));
		
		//fake $_SERVER['REQUEST_URI'];
		$_SERVER['REQUEST_URI'] = '/custom_page';
		
		$this->peakrouter->getRequestURI();
		//print_r($this->peakrouter);
		
		//request uri , controller , action
		$this->assertTrue($this->peakrouter->request_uri === 'custom_page');
		$this->assertTrue($this->peakrouter->controller === 'index');
		$this->assertTrue($this->peakrouter->action === 'news');
				
		//params
		$this->assertEmpty($this->peakrouter->params);
		
		//params assoc
		$this->assertEmpty($this->peakrouter->params_assoc);
	}
	   
	function testSimpleRegex2()
	{
		//add regex the short way with custom integer
		$this->peakrouter->addRegex('post/(\d+)', 'blog/post');
		
		//fake $_SERVER['REQUEST_URI'];
		$_SERVER['REQUEST_URI'] = '/post/89';
		
		$this->peakrouter->getRequestURI();
		//print_r($this->peakrouter);
		
		//request uri , controller , action
		$this->assertTrue($this->peakrouter->request_uri === 'post/89');
		$this->assertTrue($this->peakrouter->controller === 'blog');
		$this->assertTrue($this->peakrouter->action === 'post');
		
		//params
		$this->assertTrue(is_array($this->peakrouter->params));
		$this->assertTrue($this->peakrouter->params[0] == 89);
		
		//params assoc
		$this->assertEmpty($this->peakrouter->params_assoc);
		
		
		//retry but with invalid data at the end. the regex is supposed to fail and the normal request routing should happen
		$_SERVER['REQUEST_URI'] = '/post/89/test1234';
		
		$this->peakrouter->getRequestURI();
		//print_r($this->peakrouter);
		
		//request uri , controller , action
		$this->assertTrue($this->peakrouter->request_uri === 'post/89/test1234');
		$this->assertTrue($this->peakrouter->controller === 'post');
		$this->assertTrue($this->peakrouter->action === '89');
		
		//params
		$this->assertTrue(is_array($this->peakrouter->params));
		$this->assertTrue($this->peakrouter->params[0] == 'test1234');
		
		//params assoc
		$this->assertEmpty($this->peakrouter->params_assoc);
		
		return $this->peakrouter;
	
	}
	
	/**
	 * @depends testSimpleRegex2
	 */
	function testDeleteRegex(Peak\Router $router)
	{
		$this->peakrouter = $router;
			
		$this->peakrouter->deleteRegex('post/(\d+)');
		
		//fake $_SERVER['REQUEST_URI'];
		$_SERVER['REQUEST_URI'] = '/post/89';
		
		$this->peakrouter->getRequestURI();
		//print_r($this->peakrouter);
		
		//request uri , controller , action
		$this->assertTrue($this->peakrouter->request_uri === 'post/89');
		$this->assertTrue($this->peakrouter->controller === 'post');
		$this->assertTrue($this->peakrouter->action === '89');
	}
    
}