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
