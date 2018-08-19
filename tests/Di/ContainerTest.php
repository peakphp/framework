<?php

use PHPUnit\Framework\TestCase;
use Peak\Di\Container;
use Peak\Collection\Collection;

require __DIR__.'/../fixtures/di/test.php';

class ContainerTest extends TestCase
{
    
    /**
     * test new instance
     */  
    function testCreateInstance()
    {

        $container = new Container();
        $testdi = $container->create('TestDi1', [
            'value',
            [12],
            999
        ]);

        $this->assertTrue($testdi instanceof TestDi1);
        $this->assertTrue($testdi->col instanceof Collection);
        $this->assertTrue(isset($testdi->arg1));
        $this->assertTrue(isset($testdi->arg2));
        $this->assertTrue(isset($testdi->arg3));
    }

    /**
     * test new instance
     */  
    function testCreateInstanceRecursive()
    {

        $container = new Container();
        $testdi = $container->create('TestDi4', [
            'TestDi1' => [
                'value',
                [12],
                999,
            ]
        ]);

        $this->assertTrue($testdi instanceof TestDi4);
        $this->assertTrue($testdi->testdi1 instanceof TestDi1);
        $this->assertTrue($testdi->testdi1->col instanceof Collection);
        $this->assertTrue(isset($testdi->testdi1->arg1));
        $this->assertTrue(isset($testdi->testdi1->arg2));
        $this->assertTrue(isset($testdi->testdi1->arg3));



        $container = new Container();
        $testdi = $container->create('TestDi5', [
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
        $this->assertTrue($testdi->testdi4->testdi1->col instanceof Collection);
        $this->assertTrue(isset($testdi->testdi4->testdi1->arg1));
        $this->assertTrue(isset($testdi->testdi4->testdi1->arg2));
        $this->assertTrue(isset($testdi->testdi4->testdi1->arg3));


        // --- restart container ---
        $container = new Container();

        $testdi = $container->create(
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
        $this->assertTrue($testdi->testdi9->testdi->col instanceof Collection);
        $this->assertTrue($testdi->testdi9->testdi->arg1 === 'value');
    }

    /**
     * test new instance
     */  
    function testCreateInstanceWithInterface()
    {

        $container = new Container();

        $testdi7 = new TestDi7();
        $testdi7->foobar = 'foobar7';
        $container->set($testdi7);
        
        $testdi = $container->create('TestDi6');

        $this->assertTrue($testdi->testdi->foobar === 'foobar7');

        $interfaces = class_implements($testdi->testdi);
        $this->assertTrue(count($interfaces) == 1);


        // --- restart container ---
        $container = new Container();

        //both implement the same interface
        $container->set(new TestDi7());
        $container->set(new TestDi8());

        $testdi = $container->create(
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
        $container = new Container();

        //both implement the same interface
        $container->set(new TestDi7());
        $container->set(new TestDi8());

        $testdi = $container->create(
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
        $container = new Container();

        // $container->set(new Collection(['foo' => 'bar']));
        $testdi1 = $container->create('TestDi1', [
            'test',
            []
        ]);

        $container->set($testdi1);

        //both implement the same interface (TestDiInterface)
        $container->set(new TestDi7());
        $container->set(new TestDi8());
        
        //TestDiInterface2
        $testdi9 = $container->create('TestDi9', [['FOOBAR!']]);
        $container->set($testdi9);

        $testdi13 = $container->create(
            'TestDi13', 
            [],
            ['TestDiInterface' => 'TestDi8'] 
        );

        $this->assertTrue($testdi13->testdi12->testdi->barfoo === 'foobar8');


        $testdi13 = $container->create(
            'TestDi13', 
            [],
            ['TestDiInterface' => 'TestDi7'] 
        );

        //die();

        $this->assertTrue($testdi13->testdi12->testdi->foobar === 'foobar7');
    }

    function testCreateInstanceWithClosure()
    {
        $container = new Container();

        $container->set(new TestDi7());

        $testdi13 = $container->create(
            'TestDi13', 
            [],
            [
                'TestDiInterface' => function() {
                    return new TestDi7();
                },
                'TestDiInterface2' => function() {
                    return new TestDi9(
                        new TestDi1(
                            new Peak\Collection\Collection(),
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
        $container = new Container();

        $container->set(new Peak\Collection\Collection(['foo' => 'barNOTexplicit']));

        $testdi1 = $container->create(
            'TestDi1', //class
            [
                'value', // arguments
                [12],
                999
            ],
            [
                //explicit
                'Peak\Collection\Collection' => function() {
                    return new Peak\Collection\Collection(['foo' => 'barexplicit']);
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
        $container = new Container();
        try {

            //both implement the same interface
            $container->set(new TestDi7());
            $container->set(new TestDi8());
            
            //TestDi6 has an interface as dependency, both TestDi7 and TestDi8 qualify
            //but container doesn't know which one to use so it throw an LogicException 
            $testdi = $container->create('TestDi6');
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
        $container = new Container();
        try {
            //$this->expectException(Exception::class);
            $testdi = $container->create('iDontExists', [
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
        $container = new Container();
        try {
            //$this->expectException(Exception::class);
            $testdi = $container->create('TestDi2');
        }
        catch(Exception $e) {}

        $this->assertFalse(isset($testdi));
    }

    /**
     * test exception with class dependencie(s) unknow name(s)
     */  
    function testGetHasInstance()
    {
        $container = new Container();
        $container->set(new TestDi3());

        $this->assertTrue($container->has('TestDi3'));
        $this->assertFalse($container->has('UnknowClass'));

        $testdi = $container->get('TestDi3');

        $this->assertTrue($testdi instanceof TestDi3);

        try {
            $testdi9999 = $container->get('TestDi9999');
        } catch(Exception $e) {
        }
        $this->assertTrue($e instanceof \Peak\Di\Exception\NotFoundException);
    }

    /**
     * Test delete instance
     */
    function testDeleteInstance()
    {
        $container = new Container();

        //both implement the same interface
        $container->set(new TestDi7());
        $container->set(new TestDi8());

        $this->assertTrue($container->has('TestDi7') !== null);

        $container->delete('TestDi8');
        $this->assertFalse($container->has('TestDi8'));
        $this->assertTrue($container->has('TestDi7'));
    }

    /**
     * Test get all stored instances or interfaces
     */
    function testGetInts()
    {
        $container = new Container();

        //both implement the same interface
        $container->set(new TestDi7());
        $container->set(new TestDi8());

        $interfaces = $container->getInterfaces();
        $this->assertTrue(count($interfaces) == 1);

        $instances = $container->getInstances();
        $this->assertTrue(count($instances) == 2);
    }

    /**
    * Test container resolve dependencies for object method
    */
    function testMethodCall()
    {
        $container = new Container();

        $testdi = $container->create('TestDi1', [
            'value',
            [12],
            999
        ]);

        $arguments = ['hello'];
        $explicits = [];

        $result = $container->call([$testdi, 'methodA'], $arguments, $explicits);

        $this->assertTrue($result === $arguments[0]);
    }

    /**
     * Test container resolve dependencies for object method
     */
    function testMethodCall2()
    {
//        $container = new Container();
//
//        $testdi = $container->create('TestDi1', [
//            'value',
//            [12],
//            999
//        ]);
//
//        $arguments = ['hello'];
//        $explicits = [];
//
//        $result = $container->call(function() {
//            return [
//                $testdi,
//                'methodA'
//            ];
//        }, $arguments, $explicits);
//
//        //print_r($result);
//
//        $this->assertTrue($result === $arguments[0]);
    }

    /**
     * Test call exception
     */
    function testMethodCallException()
    {
        $container = new Container();

        $testdi = $container->create('TestDi1', [
            'value',
            [12],
            999
        ]);

        $arguments = ['hello'];
        $explicits = [];

        try {
            $result = $container->call([$testdi], $arguments, $explicits);
        } catch(Exception $e) {
            $error = true;
            //echo $e->getMessage();
        }

        $this->assertTrue(isset($error));
    }

    /**
     * Test aliases
     */
    function testAliases()
    {
        $container = new Container();

        $container->addAlias('MyClassAlias', TestDi1::class);

        $testdi = $container->createAndStore('TestDi1', [
            'value',
            [12],
            999
        ]);

        //the normal way
        $testdi = $container->get(TestDi1::class);
        $this->assertTrue($testdi instanceof TestDi1);

        //the alias way
        $testdi = $container->get('MyClassAlias');
        $this->assertTrue($testdi instanceof TestDi1);
    }

    /**
     * Test add object with aliases
     */
    function testAddWithAliases()
    {
        $container = new Container();

        $testdi = $container->create('TestDi1', [
            'value',
            [12],
            999
        ]);

        $container->set($testdi, 'MyClassAlias');

        //the normal way
        $testdi = $container->get(TestDi1::class);
        $this->assertTrue($testdi instanceof TestDi1);

        //the alias way
        $testdi = $container->get('MyClassAlias');
        $this->assertTrue($testdi instanceof TestDi1);
    }

    /**
     * Test add the container instance to the container itself
     */
    function testAddItsetf()
    {
        $container = new Container();

        $container->addAlias('MyClassAlias', TestDi1::class);
        $container->addItself();

        $testdi = $container->create(TestDi15::class);
        $this->assertTrue($testdi->container instanceof \Peak\Di\Container);
    }

    /**
     * @expectedException \Exception
     */
    public function testCreateException()
    {
        $container = new Container();
        $container->enableAutowiring();
        $container->disableAutowiring();
        $container->create('UnknownClass');
    }
}
