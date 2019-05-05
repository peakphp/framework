<?php

require_once FIXTURES_PATH.'/di/context.php';

use PHPUnit\Framework\TestCase;
use Peak\Di\Container;
use Peak\Di\Binding\Prototype;
use Peak\Di\Binding\Factory;
use Peak\Di\Binding\Singleton;

use Di\InterfaceA;
use Di\InterfaceB;

use Di\A;
use Di\AA;
use Di\B;
use Di\C;
use Di\W;
use Di\X;
use Di\Y;
use Di\Z;
use Di\Finger;
use Di\Hand;
use Di\Arm;
use Di\Chest;
use Di\Body;


class ContainerBindingSingletonTest extends TestCase
{
    public function testBasic()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bind(A::class, new A);

        $class = $container->create(A::class);

        $this->assertTrue($class instanceof A);
    }

    public function testBasicWithGet()
    {
        $container = new Container();
        $container->disableAutoWiring();
        $container->bind(A::class, new A);
        $class = $container->get(A::class);
        $this->assertTrue($class instanceof A);
    }

    public function testBindInstance()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $a = new A;
        $a->foo = 'bar';
        $container->bind(A::class, $a);

        $a = $container->create(A::class);
        $this->assertTrue($a instanceof A);
        $this->assertTrue($a->foo === 'bar');
    }

    public function testBindInstanceWithString()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bind(A::class, A::class);
        $a = $container->get(A::class);
        $a->foo = 'bar';

        $aa = $container->create(A::class);
        $this->assertTrue($aa instanceof A);
        $this->assertTrue($aa->foo === 'bar');
    }

    /**
     * Test complex array definition
     */
    public function testArrayDefinition()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bind(A::class, function() {
            $a = new A;
            $a->name = 'foobar';
            return  $a;
        });

        // with arguments stored with the binding
        $container->bind(Chest::class, [
            Chest::class,
            Arm::class => [
                Arm::class,
                A::class, // will use A::class previously definition
                'foo',
            ],
            'bar',
        ]);

        $chest = $container->create(Chest::class);
        $this->assertTrue($chest instanceof Chest);
        $this->assertTrue($chest->arm->argv === 'foo');
        $this->assertTrue($chest->argv === 'bar');
        $this->assertTrue($chest->arm->a->name === 'foobar');
    }

    /**
     * Test complex array definition
     */
    public function testArrayDefinition2()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bind(A::class, function() {
            $a = new A;
            $a->name = 'foobar';
            return  $a;
        });

        // with arguments stored with the binding
        $container->bind(Chest::class, [
            Chest::class,
            Arm::class => [
                Arm::class,
                A::class, // will use A::class previously definition
                new stdClass(),
            ],
            'bar'
        ]);

        $chest = $container->create(Chest::class);
        $this->assertTrue($chest instanceof Chest);
        $this->assertInstanceOf(stdClass::class, $chest->arm->argv);
        $this->assertTrue($chest->argv === 'bar');
        $this->assertTrue($chest->arm->a->name === 'foobar');
    }

    public function testArrayDefinition3()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $a = new A();
        $a->name = 'foobar';

        $container->set($a);

        // with arguments stored with the binding
        $container->bind(Chest::class, [
            Chest::class,
            Arm::class => [
                Arm::class,
                A::class, // will use A::class previously definition
                new stdClass(),
            ],
            'bar'
        ]);

        $chest = $container->create(Chest::class);
        $this->assertTrue($chest instanceof Chest);
        $this->assertInstanceOf(stdClass::class, $chest->arm->argv);
        $this->assertTrue($chest->argv === 'bar');
        $this->assertTrue($chest->arm->a->name === 'foobar');
    }

    public function testArrayDefinition4()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $a = new A();
        $a->name = 'foobar';

        $container->set($a);

        // with arguments stored with the binding
        $container->bind(Chest::class, [
            Chest::class,
            function() use ($a) {
                return new Arm($a, 'barvar');
            },
            'bar',
        ]);

        $chest = $container->create(Chest::class);
        $this->assertTrue($chest instanceof Chest);
        $this->assertTrue('barvar' === $chest->arm->argv);
        $this->assertTrue($chest->argv === 'bar');
        $this->assertTrue($chest->arm->a->name === 'foobar');
    }

    /**
     * Bind a definition that create a singleton
     */
    public function testBindSingleton()
    {
        $container = new Container();
        $container->disableAutoWiring();

        // with arguments stored with the binding
        // when using array definition, the first element represent class
        // we want instantiate and others elements represent class constructor arguments
        $container->bind(Finger::class, [
            Finger::class,
            A::class,
            'bar',
            'foo',
        ]);

        $container->bindPrototype(A::class, A::class);

        $finger = $container->create(Finger::class);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'bar');
        $this->assertTrue($finger->arg2 === 'foo');

        $finger = $container->get(Finger::class);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'bar');
        $this->assertTrue($finger->arg2 === 'foo');

        $finger->arg1 = 'foofoo';

        $finger = $container->create(Finger::class);
        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'foofoo');

        $finger = $container->create(Finger::class, [], function(Container $c) {
            return new Finger(new A, 'Hello', 'You');
        });

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'Hello');

        $finger = $container->create(Finger::class);
        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'foofoo');

        // test delete
        $container->delete(Finger::class);
        $finger = $container->create(Finger::class);
        $this->assertTrue($finger->arg1 === 'bar');
        $this->assertTrue($finger->arg2 === 'foo');
    }


    /**
     * Bind a definition that create a singleton
     */
    public function testBindSingleton2()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bind(Finger::class, [
            Finger::class,
            A::class,
            'bar',
            'foo',
        ]);

        $finger = $container->create(Finger::class);
        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'bar');
        $finger->arg1 = 'foobar';

        $other_finger = $container->create(Finger::class);
        $this->assertTrue($other_finger->arg1 === 'foobar');
    }

    public function testBindSingleton3()
    {
        $container = new Container();
        $container->disableAutoWiring();
        $container->bind(Finger::class, [
            Finger::class,
            A::class,
        ]);
        $finger = $container->create(Finger::class, ['bar', 'foo']);
        $this->assertInstanceOf(Finger::class, $finger);
        $this->assertTrue($finger->arg1 === 'bar');
        $this->assertTrue($finger->arg2 === 'foo');
    }

    public function testBindSingleton4()
    {
        $container = new Container();
        $container->disableAutoWiring();
        $container->bind(A::class, A::class);
        $a = $container->create(A::class);
        $this->assertInstanceOf(A::class, $a);
    }

    public function testBindSingletonRandomName()
    {
        $container = new Container();
        $container->disableAutoWiring();
        $container->bind('FooBar32', A::class);
        $a = $container->create('FooBar32');
        $this->assertInstanceOf(A::class, $a);
    }

    public function testBindForInterfaceWithAutoWiring()
    {
        $container = new Container();
        $container->bind(TestDiInterface::class, TestDi7::class);
        $testdi = $container->create(TestDiInterface::class);
        $this->assertInstanceOf(TestDiInterface::class, $testdi);
        $testdi->test = 'test';
        $testdi2 = $container->get(TestDiInterface::class);
        $this->assertTrue($testdi2->test === 'test');
    }

    public function testBindForInterfaceResolving()
    {
        $container = new Container();
        $container->bind(TestDiInterface::class, TestDi7::class);
        $testdi = $container->create(TestDi6::class);
        $this->assertInstanceOf(TestDi6::class, $testdi);
        $this->assertInstanceOf(TestDi7::class, $testdi->testdi);
    }

    public function testBindingSingletonInterfaceWithNestedDependencies()
    {
        $container = new Container();
        $container->bind(TestDiInterface::class, TestDi20::class);
        $testdi = $container->get(TestDiInterface::class);
        $this->assertInstanceOf(TestDi20::class, $testdi);

        $container = new Container();
        $container->bind(TestDiInterface3::class, TestDi3::class);
        $container->bind(TestDiInterface::class, TestDi21::class);
        $testdi = $container->get(TestDiInterface::class);
        $this->assertInstanceOf(TestDi21::class, $testdi);
    }

    public function testBindingSingletonInterfaceWithNestedDependenciesLoopException()
    {
        $this->expectException(\Peak\Di\Exception\InfiniteLoopResolutionException::class);
        $container = new Container();
        $container->bind(TestDiInterface::class, TestDi22::class);
        $testdi = $container->get(TestDiInterface::class);
    }

    public function testBindingSingletonInterfaceWithArrayDefinitionAndNestedDependenciesLoopException()
    {
        $this->expectException(\Peak\Di\Exception\InfiniteLoopResolutionException::class);
        $container = new Container();
        $container->bind(TestDiInterface::class, [
            TestDiInterface::class,
            TestDi22::class
        ]);
        $testdi = $container->get(TestDiInterface::class);
    }
}
