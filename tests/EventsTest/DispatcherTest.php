<?php
use PHPUnit\Framework\TestCase;

use Peak\Events\Dispatcher;

class DispatcherTest extends TestCase
{

    public function testAttach()
    {   
        $d = new Dispatcher();  

        $d->attach('myevent', function($argv) {
            //do something
        });

        $this->assertTrue($d->hasEvent('myevent'));
        $this->assertFalse($d->hasEvent('mysecondevent'));
    }

    public function testFire()
    {   
        $d = new Dispatcher();  

        $var = 'barfoo';

        $this->assertTrue($var === 'barfoo');

        $d->attach('myevent', function($argv) use(&$var) {
            $var = $argv;
        });

        $d->fire('myevent', 'foobar');

        $this->assertTrue($var === 'foobar');
    }

    public function testMultipleFire()
    {   
        $d = new Dispatcher();  

        $int = 0;

        $d->attach('myevent', function() use(&$int) {
            ++$int;
        });
        $d->attach('myevent', function() use(&$int) {
            ++$int;
        });
        $d->attach('myevent', function() use(&$int) {
            ++$int;
        });


        $d->fire('myevent');

        $this->assertTrue($int == 3);
    }
}