<?php

require_once FIXTURES_PATH.'/di/context.php';

use PHPUnit\Framework\TestCase;
use Peak\Di\Container;
use Peak\Di\Binding\Instance;
use Peak\Di\Binding\Prototype;
use Peak\Di\Binding\Factory;
use Peak\Di\Binding\Singleton;

use Di\InterfaceA;
use Di\InterfaceB;

use Di\A;
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

/**
 * Cover binding aspect
 */
class ContainerBindingTest extends TestCase
{
    /**
     * Create A instance
     */
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

    /**
     * @expectedException \Peak\Di\Exception\ClassDefinitionNotFoundException
     */
    public function testExceptionWithCreate()
    {
        $container = new Container();
        $container->disableAutoWiring();
        $class = $container->create(A::class);
    }

    /**
     * @expectedException \Psr\Container\NotFoundExceptionInterface
     */
    public function testExceptionWithGet()
    {
        $container = new Container();
        $container->disableAutoWiring();
        $class = $container->get(A::class);
    }

    /**
     * Bind instance
     */
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

    /**
     * Test bypassing definition binding
     */
    public function testBindFactory()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bindFactory(Finger::class, function (Container $c, $args) {
            return new Finger(new A, 'factory', 'bar');
        });

        $finger = $container->create(Finger::class, ['pass argument to closure']);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'factory');
    }

    /**
     * Test bypassing definition binding
     */
    public function testSetDefinitions()
    {
        $container = new Container();
        $container->disableAutoWiring();

        // bind a factory for Finger::class
        $container

            ->bindFactory(Finger::class, function (Container $c) {
                return new Finger(new A, 'factory', 'bar');
            })

            ->bind(A::class, new A)

            ->bindFactory(Finger::class, function (Container $c) {
                return new Finger(new A, 'factory', 'bar');
            })

            ->bindPrototype(Arm::class, [
                Arm::class,
                A::class,
                'arg'
            ]);



        $finger = $container->create(Finger::class);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'factory');

        $arm = $container->create(Arm::class);
        $this->assertTrue($arm instanceof Arm);
    }

    public function testBindingObject()
    {
        $prototype = new Prototype('myname', 'definition');
        $this->assertTrue($prototype->getName() === 'myname');
        $this->assertTrue($prototype->getType() === 2);
        $this->assertTrue($prototype->getDefinition() === 'definition');
    }
}
