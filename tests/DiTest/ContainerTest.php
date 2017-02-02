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
    }
}

class TestDi1
{
    public $col;
    public $arg1;
    public $arg2;
    public $arg3;
    
    function __construct(\Pek\Collection $col, $arg1, array $arg2, $arg3 = [])
    {
        $this->col = $col;
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->arg3 = $arg3;
    }

}