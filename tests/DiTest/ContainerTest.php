<?php
use PHPUnit\Framework\TestCase;

use Peak\Di\Container;


class ContainerTest extends TestCase
{
    
    /**
     * test new instance
     */  
    function testCreateInstance()
    {

        $container = new \Peak\Di\Container();
        $testdi = $container->instantiate('TestDi1', [
            'value',
            [12],
            999
        ]);

        $this->assertTrue($testdi instanceof TestDi1);
        $this->assertTrue($testdi->col instanceof \Peak\Collection);
        $this->assertTrue(isset($testdi->arg1));
        $this->assertTrue(isset($testdi->arg2));
        $this->assertTrue(isset($testdi->arg3));
    }

    /**
     * test new instance
     */  
    function testCreateInstanceRecursive()
    {

        $container = new \Peak\Di\Container();
        $testdi = $container->instantiate('TestDi4', [
            'TestDi1' => [
                'value',
                [12],
                999,
            ]
        ]);

        $this->assertTrue($testdi instanceof TestDi4);
        $this->assertTrue($testdi->testdi1 instanceof TestDi1);
        $this->assertTrue($testdi->testdi1->col instanceof \Peak\Collection);
        $this->assertTrue(isset($testdi->testdi1->arg1));
        $this->assertTrue(isset($testdi->testdi1->arg2));
        $this->assertTrue(isset($testdi->testdi1->arg3));



        $container = new \Peak\Di\Container();
        $testdi = $container->instantiate('TestDi5', [
            'TestDi4' => [
                'TestDi1' => [
                    'value',
                    [12],
                    999,
                ],
            ],
        ]);

        $this->assertTrue($testdi instanceof TestDi5);
        $this->assertTrue($testdi->testdi4 instanceof TestDi4);
        $this->assertTrue($testdi->testdi4->testdi1->col instanceof \Peak\Collection);
        $this->assertTrue(isset($testdi->testdi4->testdi1->arg1));
        $this->assertTrue(isset($testdi->testdi4->testdi1->arg2));
        $this->assertTrue(isset($testdi->testdi4->testdi1->arg3));


        // --- restart container ---
        $container = new \Peak\Di\Container();

        $testdi = $container->instantiate(
            'TestDi10', //classname
            [
                'foobar10', //arguments....
                'TestDi9' => [
                    ['myarray'],
                    'TestDi1' => [
                        'value',
                        [12],
                        999,
                    ],
                ]
            ]
        ); 

        $this->assertTrue($testdi->say === 'foobar10');
        $this->assertTrue($testdi->testdi9->say === 'hello');
        $this->assertTrue($testdi->testdi9->testdi->col instanceof \Peak\Collection);
        $this->assertTrue($testdi->testdi9->testdi->arg1 === 'value');
    }

    /**
     * test new instance
     */  
    function testCreateInstanceWithInterface()
    {

        $container = new \Peak\Di\Container();

        $testdi7 = new TestDi7();
        $testdi7->foobar = 'foobar7';
        $container->addInstance($testdi7);
        
        $testdi = $container->instantiate('TestDi6');

        $this->assertTrue($testdi->testdi->foobar === 'foobar7');

        $interfaces = class_implements($testdi->testdi);
        $this->assertTrue(count($interfaces) == 1);


        // --- restart container ---
        $container = new \Peak\Di\Container();

        //both implement the same interface
        $container->addInstance(new TestDi7()); 
        $container->addInstance(new TestDi8());

        $testdi = $container->instantiate(
            'TestDi6', //classname
            [''], //arguments.... here none
            ['TestDiInterface' => 'TestDi7'] //explicit relationship for an interface
        ); 

        $this->assertTrue(isset($testdi->testdi->foobar));
    }

    /**
     * test new instance
     */  
    function testCreateInstanceWithInterface2()
    {
        // --- restart container ---
        $container = new \Peak\Di\Container();

        //both implement the same interface
        $container->addInstance(new TestDi7()); 
        $container->addInstance(new TestDi8());

        $testdi = $container->instantiate(
            'TestDi11', //classname
            [
                'foobar10', //arguments....
                'TestDi9' => [
                    ['myarray'],
                    'TestDi1' => [
                        'value',
                        [12],
                        999,
                    ],
                ]
            ],
            ['TestDiInterface' => 'TestDi8'] //explicit declaration for interface
        ); 

        $this->assertTrue($testdi->say === 'foobar10');
    }

 
    /**
     * test exception with unknow class
     */  
    function testCreateInstanceWithInterfaceException()
    {
        $container = new \Peak\Di\Container();
        try {

            //both implement the same interface
            $container->addInstance(new TestDi7()); 
            $container->addInstance(new TestDi8());
            
            //TestDi6 has an interface as dependency, both TestDi7 and TestDi8 qualify
            //but container doesn't know which one to use so it throw an LogicException 
            $testdi = $container->instantiate('TestDi6'); 
        }
        catch(Exception $e) {
            $ename = get_class($e);
        }

        $this->assertTrue(isset($ename));
        $this->assertTrue($ename === 'LogicException');
    }

    /**
     * test exception with unknow class
     */  
    function testException()
    {
        $container = new \Peak\Di\Container();
        try {
            //$this->expectException(Exception::class);
            $testdi = $container->instantiate('iDontExists', [
                'value',
                [12],
                999
            ]);
        }
        catch(Exception $e) {}

        $this->assertFalse(isset($testdi));
    }

    /**
     * test exception with class dependencie(s) unknow name(s)
     */  
    function testExceptionDependencies()
    {
        $container = new \Peak\Di\Container();
        try {
            //$this->expectException(Exception::class);
            $testdi = $container->instantiate('TestDi2');
        }
        catch(Exception $e) {}

        $this->assertFalse(isset($testdi));
    }

    /**
     * test exception with class dependencie(s) unknow name(s)
     */  
    function testGetHasInstance()
    {
        $container = new \Peak\Di\Container();
        $container->addInstance(new TestDi3());

        $this->assertTrue($container->hasInstance('TestDi3'));
        $this->assertFalse($container->hasInstance('UnknowClass'));

        $testdi = $container->getInstance('TestDi3');

        $this->assertTrue($testdi instanceof TestDi3);

        $testdi9999 = $container->getInstance('TestDi9999');
        $this->assertTrue(is_null($testdi9999));
    }

}

interface TestDiInterface {}

interface TestDiInterface2 {
    public function __construct(TestDi1 $testdi, array $args = []);
}

class TestDi1
{
    public $col;
    public $arg1;
    public $arg2;
    public $arg3;
    
    function __construct(\Peak\Collection $col, $arg1, array $arg2, $arg3 = [])
    {
        $this->col = $col;
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->arg3 = $arg3;
    }
}

class TestDi2
{    
    function __construct(\I\Dont\Exists\Collection $col) {}
}

class TestDi3
{    
    function __construct() {}
}

class TestDi4
{
    public $testdi1;

    function __construct(TestDi1 $di1) 
    {
        $this->testdi1 = $di1;
    }
}

class TestDi5
{
    public $testdi4;

    function __construct(TestDi4 $di4) 
    {
        $this->testdi4 = $di4;
    }
}


class TestDi6
{
    public $testdi;

    function __construct(TestDiInterface $di) 
    {
        $this->testdi = $di;
    }
}


class TestDi7 implements TestDiInterface
{
    public $foobar = 'foobar';

    function __construct() {}
}


class TestDi8 implements TestDiInterface
{
    public $barfoo = 'foobar';

    function __construct() {}
}

class TestDi9 implements TestDiInterface2
{
    function __construct(TestDi1 $testdi, array $args = [])
    {
        $this->say = 'hello';
        $this->testdi = $testdi;
    }
}

class TestDi10
{
    function __construct(TestDi9 $testdi9, $string)
    {
        $this->say = $string;
        $this->testdi9 = $testdi9;
    }
}


class TestDi11
{
    function __construct(TestDi6 $testdi6, $string)
    {
        $this->say = $string;
    }
}
