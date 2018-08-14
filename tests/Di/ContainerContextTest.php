<?php

require_once FIXTURES_PATH.'/di/context.php';

use PHPUnit\Framework\TestCase;
use Peak\Di\Container;

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
use Di\Body;

/**
 * Cover almost same stuff than Container Test but in a more meaningful context
 */
class ContainerContextTest extends TestCase
{
    /**
     * Create A, B and C on the fly for Hand
     */
    public function testBasic()
    {
        $container = new Container();

        $hand = $container->create(Hand::class);

        $this->assertTrue($hand instanceof Hand);
        $this->assertTrue($hand->a instanceof A);
        $this->assertTrue($hand->b instanceof B);
        $this->assertTrue($hand->c instanceof C);
    }

    /**
     * Resolve
     */
    public function testBasicWithArgmuments()
    {
        $container = new Container();

        $finger = $container->create(Finger::class, [
            'foo',
            'bar',
        ]);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->a instanceof A);
        $this->assertTrue($finger->arg1 === 'foo');
        $this->assertTrue($finger->arg2 === 'bar');
    }

    /**
     * Create A and B and reuse stored C instance
     */
    public function testReuseStoredInstance()
    {
        $container = new Container();

        $c = new C();
        $c->foo = 'bar';

        $container->add($c);

        $hand = $container->create(Hand::class);

        $this->assertTrue($hand instanceof Hand);
        $this->assertTrue($hand->a instanceof A);
        $this->assertTrue($hand->b instanceof B);
        $this->assertTrue($hand->c instanceof C);
        $this->assertTrue($hand->c->foo === 'bar');
    }

    /**
     * Create A, B and C, skipping stored C instance
     */
    public function testSkipStoredInstance()
    {
        $container = new Container();

        $c = new C();
        $c->foo = 'bar';

        $container->add($c);

        $hand = $container->create(Hand::class, [], [
            C::class => function(Container $c) {
                return $c->create(C::class); //create a new instance of C instead of using the stored one
            }
        ]);

        $this->assertTrue($hand instanceof Hand);
        $this->assertTrue($hand->a instanceof A);
        $this->assertTrue($hand->b instanceof B);
        $this->assertTrue($hand->c instanceof C);
        $this->assertTrue(!isset($hand->c->foo));
    }

    /**
     * Resolve interface
     * Should throw an exception since their no stored instance matching InterfaceA
     */
    public function testResolveInterfaceException()
    {
        $container = new Container();

        try {
            // Arm constructor need an InterfaceA instance but there is none
            $arm = $container->create(Arm::class);
        } catch(Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }

    /**
     * Resolve interface manually
     */
    public function testResolveInterfaceManually()
    {
        $container = new Container();

        $arm = $container->create(Arm::class, [], [
            InterfaceA::class => function(Container $c) {
                return new A;
            }
        ]);

        $this->assertTrue($arm instanceof Arm);
        $this->assertTrue($arm->a instanceof A);
    }

    /**
     * Resolve interface with stored instance
     */
    public function testResolveInterfaceWithStoredInstance()
    {
        $container = new Container();

        $container->add(new W); // class W implements InterfaceA

        $arm = $container->create(Arm::class);

        $this->assertTrue($arm instanceof Arm);
        $this->assertTrue($arm->a instanceof W);
    }

    /**
     * Resolve interface when 2 or more stored instances implements the same interface
     * Should throw an exception
     */
    public function testResolveInterfaceWithStoredException()
    {
        $container = new Container();

        $container->add(new A); // class A implements InterfaceA
        $container->add(new W); // class W implements InterfaceA

        try {
            $arm = $container->create(Arm::class);
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }

    /**
     * Resolve interface when 2 or more stored instance implements the same interface name
     */
    public function testResolveInterfaceWithStored()
    {
        $container = new Container();

        $container->add(new A); // class A implements InterfaceA
        $container->add(new W); // class W implements InterfaceA

        $arm = $container->create(Arm::class, [], [
            InterfaceA::class => W::class // tell the container to use stored instance W for InterfaceA
        ]);

        $this->assertTrue($arm instanceof Arm);
        $this->assertTrue($arm->a instanceof W);
    }

    /**
     * Resolve interface when 2 or more stored instance implements the same interface name
     */
    public function testResolveInterfaceBypassStored()
    {
        $container = new Container();

        $container->add(new A); // class A implements InterfaceA
        $container->add(new W); // class W implements InterfaceA

        $arm = $container->create(Arm::class, [], [
            InterfaceA::class => function(Container $c) {
                // bypass stored instances and create a new one satisfying InterfaceA dependency
                return new X; // X implements also InterfaceA
            }
        ]);

        $this->assertTrue($arm instanceof Arm);
        $this->assertTrue($arm->a instanceof X);
    }

    /**
     * Resolve multiple interfaces with mixed explicit techniques
     */
    public function testMixedResolution()
    {
        $container = new Container();

        $container->add(new A); // class A implements InterfaceA
        $container->add(new W); // class W implements InterfaceA

        $body = $container->create(Body::class, ['foo'], [
            InterfaceA::class => W::class, // take W stored instance in $container
            InterfaceB::class => function(Container $c) {
                // their is no definition for InterfaceB so create
                // an Y instance which implement InterfaceB
                return $c->create(Y::class);
            }
        ]);

        $this->assertTrue($body instanceof Body);
        $this->assertTrue($body->a instanceof W);
        $this->assertTrue($body->b instanceof Y);
        $this->assertTrue($body->argv === 'foo');
    }
}
