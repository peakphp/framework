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
        $testdi = $container->instanciate('TestDi1', [
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
     * test exception with unknow class
     */  
    function testException()
    {
        $container = new \Peak\Di\Container();
        try {
            //$this->expectException(Exception::class);
            $testdi = $container->instanciate('iDontExists', [
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
            $testdi = $container->instanciate('TestDi2');
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