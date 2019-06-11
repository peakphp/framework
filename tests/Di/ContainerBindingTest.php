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

/**
 * Cover general binding aspect
 */
class ContainerBindingTest extends TestCase
{
    public function testExceptionWithCreate()
    {
        $this->expectException(\Peak\Di\Exception\ClassDefinitionNotFoundException::class);
        $container = new Container();
        $container->disableAutoWiring();
        $class = $container->create(A::class);
    }

    public function testExceptionWithGet()
    {
        $this->expectException(\Psr\Container\NotFoundExceptionInterface::class);
        $container = new Container();
        $container->disableAutoWiring();
        $class = $container->get(A::class);
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

            ->bindSingleton(A::class, new A)

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
}
