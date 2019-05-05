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


class ContainerBindingPrototypeTest extends TestCase
{

    public function testBindingObject()
    {
        $prototype = new Prototype('myname', 'definition');
        $this->assertTrue($prototype->getName() === 'myname');
        $this->assertTrue($prototype->getType() === 2);
        $this->assertTrue($prototype->getDefinition() === 'definition');
    }

    /**
     * Bind a definition that create a new instance each time
     */
    public function testBindPrototype()
    {
        $container = new Container();
        $container->disableAutoWiring();

        // with arguments stored with the binding
        $container->bindPrototype(Finger::class, [
            Finger::class,
            A::class,
            'bar',
            'foo',
        ]);

        $finger = $container->create(Finger::class);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'bar');
        $this->assertTrue($finger->arg2 === 'foo');

        // without arguments
        $container->bindPrototype(Finger::class, [
            Finger::class,
            A::class,
        ]);

        $finger = $container->create(Finger::class, [
            'jane',
            'doo'
        ]);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'jane');
        $this->assertTrue($finger->arg2 === 'doo');
    }

    public function testBindPrototypeException()
    {
        $this->expectException(Exception::class);
        $container = new Container();
        $container->disableAutoWiring();

        $container->bindPrototype(Finger::class, null);
        $finger = $container->create(Finger::class);
    }

    /**
     * Test complex array definition
     */
    public function testArrayDefinitionWithPrototype()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bind(A::class, function() {
            $a = new A;
            $a->name = 'foobar';
            return  $a;
        });

        // with arguments stored with the binding
        $container->bindPrototype(Chest::class, [
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
        $this->assertTrue(!isset($chest->arm->a->name));
    }

    /**
     * Test bypassing definition binding
     */
    public function testBindPrototypeExplicit()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bindPrototype(Finger::class, [
            Finger::class,
            A::class,
            'test',
            'test2'
        ]);

        $finger = $container->create(Finger::class, [], [
            Finger::class,
            A::class,
            'foo',
            'bar'
        ]);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'foo');
    }

    public function testBindingPrototypeInterfaceWithNestedDependencies()
    {
        $container = new Container();
        $container->bind(TestDiInterface3::class, TestDi3::class);
        $container->bindPrototype(TestDiInterface::class, TestDi21::class);
        $testdi = $container->get(TestDiInterface::class);
        $testdi2 = $container->get(TestDiInterface::class);
        $this->assertTrue($testdi !== $testdi2);
    }

    public function testBindingPrototypeInterfaceWithNestedDependenciesLoopException()
    {
        $this->expectException(\Peak\Di\Exception\InfiniteLoopResolutionException::class);
        $container = new Container();
        $container->bindPrototype(TestDiInterface::class, TestDi22::class);
        $testdi = $container->get(TestDiInterface::class);
    }

    public function testBindingPrototypeInterfaceWithArrayDefinitionAndNestedDependenciesLoopException()
    {
        $this->expectException(\Peak\Di\Exception\InfiniteLoopResolutionException::class);
        $container = new Container();
        $container->bindPrototype(TestDiInterface::class, [
            TestDiInterface::class,
            TestDi22::class
        ]);
        $testdi = $container->get(TestDiInterface::class);
    }
}
