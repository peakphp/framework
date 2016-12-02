<?php
use PHPUnit\Framework\TestCase;

/**
 * @package    Peak\Chrono
 */
class ChronoTest extends TestCase
{
	/**
     * test global chrono start and stop
     */
    function testGlobalChrono()
    {   	
        Peak\Chrono::start();
        usleep(149992);
        Peak\Chrono::stop();
        //echo Peak\Chrono::getMs(null,20);
        $this->assertTrue(Peak\Chrono::get() >= 0.10);
    }
    
    /**
     * test global chrono start() and get()
     */
    function testGlobalChrono2()
    {   	
        Peak\Chrono::start();
        usleep(149992);
        $this->assertTrue(Peak\Chrono::get() >= 0.10);
        //previous get() should have stop the global timer
        $this->assertTrue(Peak\Chrono::isCompleted());
    }
    
    /**
     * test global chrono verifications
     */
    function testGlobalChronoChecks()
    {
        $this->assertFalse(Peak\Chrono::isOn());
        $this->assertTrue(Peak\Chrono::isCompleted());
        
        Peak\Chrono::start();
        $this->assertTrue(Peak\Chrono::isOn());
        $this->assertFalse(Peak\Chrono::isCompleted());
        
        Peak\Chrono::resetAll();
        $this->assertFalse(Peak\Chrono::isOn());
        $this->assertFalse(Peak\Chrono::isCompleted());
        
        Peak\Chrono::start();
        Peak\Chrono::stop();
        $this->assertTrue(Peak\Chrono::isCompleted());
        $this->assertFalse(Peak\Chrono::isOn()); 
        
        Peak\Chrono::start();
        $time = Peak\Chrono::get();
        $this->assertTrue(Peak\Chrono::isCompleted());
        $this->assertFalse(Peak\Chrono::isOn());        
    }
    
    /**
     * test custom chrono start() and stop()
     */
    function testCustomChrono2()
    {
        Peak\Chrono::start('timer1');
        usleep(100000);
        Peak\Chrono::stop('timer1');
        $this->assertTrue(Peak\Chrono::get('timer1') >= 0.10);
        $this->assertTrue(Peak\Chrono::isCompleted('timer1'));     
    }
    
    /**
     * test custom chrono start() and get()
     */
    function testCustomChrono()
    {
        Peak\Chrono::start('timer1');
        usleep(100000);
        $this->assertTrue(Peak\Chrono::get('timer1') >= 0.10);
        $this->assertTrue(Peak\Chrono::isCompleted('timer1'));    
    }
    
    /**
     * test custom chrono verifications
     */
    function testCustomChronoChecks()
    {
        $this->assertFalse(Peak\Chrono::isOn('timser1'));
        $this->assertTrue(Peak\Chrono::isCompleted('timer1'));
        
        Peak\Chrono::start('timer1');
        $this->assertTrue(Peak\Chrono::isOn('timer1'));
        $this->assertFalse(Peak\Chrono::isCompleted('timer1'));
        
        Peak\Chrono::resetAll();
        $this->assertFalse(Peak\Chrono::isOn('timer1'));
        $this->assertFalse(Peak\Chrono::isCompleted('timer1'));
        
        Peak\Chrono::start('timer1');
        Peak\Chrono::stop('timer1');
        $this->assertTrue(Peak\Chrono::isCompleted('timer1'));
    }
    	  
}