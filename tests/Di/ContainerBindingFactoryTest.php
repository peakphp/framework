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

class ContainerBindingFactoryTest extends TestCase
{
    /**
     * Test bypassing definition binding
     */
    public function testBindFactory()
    {
        $container = new Container();
        $container->disableAutoWiring();

        $container->bindFactories([
            Finger::class => function (Container $c, $args) {
                return new Finger(new A, 'factory', $args[0] ?? 'bar');
            }
        ]);

        $finger = $container->create(Finger::class, ['pass argument to closure']);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'factory');
        $this->assertTrue($finger->arg2 === 'pass argument to closure');

        $finger = $container->create(Finger::class);

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'factory');
        $this->assertTrue($finger->arg2 === 'bar');

        $finger = $container->create(Finger::class, [], function() {
            return new Finger(new A, 'explicit', 'factory');
        });

        $this->assertTrue($finger instanceof Finger);
        $this->assertTrue($finger->arg1 === 'explicit');
        $this->assertTrue($finger->arg2 === 'factory');
    }
}
