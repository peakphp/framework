<?php
use PHPUnit\Framework\TestCase;

use Peak\Di\Container;

require __DIR__.'/../fixtures/di/test.php';

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
        $this->assertTrue($testdi->col instanceof \Peak\Common\Collection);
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
        $this->assertTrue($testdi->testdi1->col instanceof \Peak\Common\Collection);
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
        $this->assertTrue($testdi->testdi4->testdi1->col instanceof \Peak\Common\Collection);
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
        $this->assertTrue($testdi->testdi9->testdi->col instanceof \Peak\Common\Collection);
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

    function testCreateInstanceWithInterface3()
    {
        $container = new \Peak\Di\Container();

        // $container->addInstance(new \Peak\Common\Collection(['foo' => 'bar']));
        $testdi1 = $container->instantiate('TestDi1', [
            'test',
            []
        ]);

        $container->addInstance($testdi1);

        //both implement the same interface (TestDiInterface)
        $container->addInstance(new TestDi7()); 
        $container->addInstance(new TestDi8()); 
        
        //TestDiInterface2
        $testdi9 = $container->instantiate('TestDi9', [['FOOBAR!']]);
        $container->addInstance($testdi9); 

        $testdi13 = $container->instantiate(
            'TestDi13', 
            [],
            ['TestDiInterface' => 'TestDi8'] 
        );

        $this->assertTrue($testdi13->testdi12->testdi->barfoo === 'foobar8');


        $testdi13 = $container->instantiate(
            'TestDi13', 
            [],
            ['TestDiInterface' => 'TestDi7'] 
        );

        //die();

        $this->assertTrue($testdi13->testdi12->testdi->foobar === 'foobar7');
    }

    function testCreateInstanceWithClosure()
    {
        $container = new \Peak\Di\Container();

        $container->addInstance(new TestDi7()); 

        $testdi13 = $container->instantiate(
            'TestDi13', 
            [],
            [
                'TestDiInterface' => function() {
                    return new TestDi7();
                },
                'TestDiInterface2' => function() {
                    return new TestDi9(
                        new TestDi1(
                            new Peak\Common\Collection(),
                            'Test',
                            ['a', 'b']
                        )
                    );
                },
            ] 
        );

        //print_r($testdi13);

        $this->assertTrue($testdi13->testdi12->testdi->foobar === 'foobar7');
    }

    function testCreateInstanceWithClosure2()
    {
        $container = new \Peak\Di\Container();

        $container->addInstance(new Peak\Common\Collection(['foo' => 'barNOTexplicit']));

        $testdi1 = $container->instantiate(
            'TestDi1', //class
            [
                'value', // arguments
                [12],
                999
            ],
            [
                //explicit
                'Peak\Common\Collection' => function() {
                    return new Peak\Common\Collection(['foo' => 'barexplicit']);
                }
            ]
        );

        //print_r($testdi1);

        $this->assertTrue($testdi1->col->foo === 'barexplicit');
        $this->assertFalse($testdi1->col->foo === 'barNOTexplicit');

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
        $this->assertTrue($ename === 'Exception');
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

    function testDeleteInstance()
    {
        $container = new \Peak\Di\Container();

        //both implement the same interface
        $container->addInstance(new TestDi7()); 
        $container->addInstance(new TestDi8());

        $this->assertTrue($container->getInstance('TestDi7') !== null);

        $container->deleteInstance('TestDi8');
        $this->assertTrue($container->getInstance('TestDi8') === null);
        $this->assertTrue($container->getInstance('TestDi7') !== null);
    }

    function testGetInts()
    {
        $container = new \Peak\Di\Container();

        //both implement the same interface
        $container->addInstance(new TestDi7()); 
        $container->addInstance(new TestDi8());

        $interfaces = $container->getInterfaces();
        $this->assertTrue(count($interfaces) == 1);

        $instances = $container->getInstances();
        $this->assertTrue(count($instances) == 2);
    }

    function testMethodCall()
    {
        $container = new \Peak\Di\Container();

        $testdi = $container->instantiate('TestDi1', [
            'value',
            [12],
            999
        ]);

        $arguments = ['hello'];
        $explicits = [];

        $result = $container->call([$testdi, 'methodA'], $arguments, $explicits);

        //print_r($result);

        $this->assertTrue($result === $arguments[0]);
    }
}