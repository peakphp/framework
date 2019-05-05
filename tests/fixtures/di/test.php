<?php

use Peak\Collection\Collection;

interface TestDiInterface {}

interface TestDiInterface2 {
    public function __construct(TestDi1 $testdi, array $args = []);
}

interface TestDiInterface3 {}

class TestDi1
{
    public $col;
    public $arg1;
    public $arg2;
    public $arg3;
    
    function __construct(Collection $col, $arg1, array $arg2, $arg3 = [])
    {
        $this->col = $col;
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->arg3 = $arg3;
    }

    function methodA(Collection $col, $arg1)
    {
        return $arg1;
    }
}

class TestDi2
{    
    function __construct(\I\Dont\Exists\Collection $col) {}
}

class TestDi3 implements TestDiInterface3
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
    public $foobar = 'foobar7';

    function __construct() {}
}


class TestDi8 implements TestDiInterface
{
    public $barfoo = 'foobar8';

    function __construct() {}
}

class TestDi9 implements TestDiInterface2
{
    function __construct(TestDi1 $testdi, array $args = [])
    {
        $this->say = 'hello';
        $this->testdi = $testdi;
        $this->args = $args;
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

class TestDi12
{
    function __construct(TestDiInterface $testdi, TestDiInterface2 $testdi2)
    {
        $this->testdi = $testdi;
        $this->testdi2 = $testdi2;
    }
}

class TestDi13
{
    function __construct(TestDi12 $testdi12, $string = null)
    {
        $this->testdi12 = $testdi12;
        $this->say = $string;
    }
}

class TestDi14
{
    function __construct(TestDi12 $testdi12, TestDiInterface $testdiInt)
    {
        $this->testdi12 = $testdi12;
        $this->testdiInt = $testdiInt;
    }
}

class TestDi15
{
    function __construct(\Peak\Di\Container $container)
    {
        $this->container = $container;
    }
}

interface InterfaceTestDi16 {}

abstract class AbstractTestDi16 implements InterfaceTestDi16 {}

class TestDi16FromAbstract extends AbstractTestDi16 {}

class TestDi16
{
    public $abstractTestDi;

    function __construct(InterfaceTestDi16 $abstractTestDi = null, Collection $coll = null)
    {
        $this->abstractTestDi = $abstractTestDi;
    }
}

class TestDi17
{
    function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }
}


class TestDi18
{
    public $testdi1;
    public $testdi2;

    function __construct(TestDiInterface $di1, TestDiInterface3 $di2)
    {
        $this->testdi1 = $di1;
        $this->testdi2 = $di2;
    }
}

class TestDi19 implements TestDiInterface
{
}

class TestDi20 implements TestDiInterface
{
    /**
     * @var Collection
     */
    public $coll;

    public function __construct(Collection $coll)
    {
        $this->coll = $coll;
    }
}

class TestDi21 implements TestDiInterface
{
    /**
     * @var TestDiInterface3
     */
    public $testdi;

    public function __construct(TestDiInterface3 $testdi)
    {
        $this->testdi = $testdi;
    }
}

// this class interface should be unresolvable since it has a dependency on the same interface name in the constructor
class TestDi22 implements TestDiInterface
{
    /**
     * @var TestDiInterface
     */
    public $testdi;

    public function __construct(TestDiInterface $testdi)
    {
        $this->testdi = $testdi;
    }
}

