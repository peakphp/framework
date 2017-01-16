<?php
use PHPUnit\Framework\TestCase;

use Peak\Events\Dispatcher;

class DispatcherTest extends TestCase
{

    public function testAttach()
    {   
        $d = new Dispatcher();  

        $d->attach('myevent', function($argv, $data) {
            print_r($argv);
            print_r($data);
            //echo 'hello'.$config->get('db1.driver').'<br>';
        });

        $this->assertTrue($d->hasEvent('myevent'));
        $this->assertFalse($d->hasEvent('mysecondevent'));
    }
}