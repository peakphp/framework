<?php
use PHPUnit\Framework\TestCase;

/**
 * @package    Peak\Common\Chrono
 */
class ChronoTest extends TestCase
{
	/**
     * test global chrono start and stop
     */
    function testGlobalChrono()
    {   	
        Peak\Common\Chrono::start();
        usleep(149992);
        Peak\Common\Chrono::stop();
        $this->assertTrue(Peak\Common\Chrono::get() >= 0.10);
    }
    
    /**
     * test global chrono start() and get()
     */
    function testGlobalChrono2()
    {   	
        Peak\Common\Chrono::start();
        usleep(149992);
        $this->assertTrue(Peak\Common\Chrono::get() >= 0.10);
        //previous get() should have stop the global timer
        $this->assertTrue(Peak\Common\Chrono::isCompleted());
    }
    
    /**
     * test global chrono verifications
     */
    function testGlobalChronoChecks()
    {
        $this->assertFalse(Peak\Common\Chrono::isOn());
        $this->assertTrue(Peak\Common\Chrono::isCompleted());
        
        Peak\Common\Chrono::start();
        $this->assertTrue(Peak\Common\Chrono::isOn());
        $this->assertFalse(Peak\Common\Chrono::isCompleted());
        
        Peak\Common\Chrono::resetAll();
        $this->assertFalse(Peak\Common\Chrono::isOn());
        $this->assertFalse(Peak\Common\Chrono::isCompleted());
        
        Peak\Common\Chrono::start();
        Peak\Common\Chrono::stop();
        $this->assertTrue(Peak\Common\Chrono::isCompleted());
        $this->assertFalse(Peak\Common\Chrono::isOn()); 
        
        Peak\Common\Chrono::start();
        $time = Peak\Common\Chrono::get();
        $this->assertTrue(Peak\Common\Chrono::isCompleted());
        $this->assertFalse(Peak\Common\Chrono::isOn());        
    }
    
    /**
     * test custom chrono start() and stop()
     */
    function testCustomChrono2()
    {
        Peak\Common\Chrono::start('timer1');
        usleep(190000);
        Peak\Common\Chrono::stop('timer1');
        $this->assertTrue(Peak\Common\Chrono::get(2,'timer1') >= 0.10);
        $this->assertTrue(Peak\Common\Chrono::isCompleted('timer1'));     
    }
    
    /**
     * test custom chrono start() and get()
     */
    function testCustomChrono()
    {
        Peak\Common\Chrono::start('timer1');
        usleep(100000);
        $this->assertTrue(Peak\Common\Chrono::get(2,'timer1') >= 0.10);
        $this->assertTrue(Peak\Common\Chrono::isCompleted('timer1'));    
    }
    
    /**
     * test custom chrono verifications
     */
    function testCustomChronoChecks()
    {
        $this->assertFalse(Peak\Common\Chrono::isOn('timser1'));
        $this->assertTrue(Peak\Common\Chrono::isCompleted('timer1'));
        
        Peak\Common\Chrono::start('timer1');
        $this->assertTrue(Peak\Common\Chrono::isOn('timer1'));
        $this->assertFalse(Peak\Common\Chrono::isCompleted('timer1'));
        
        Peak\Common\Chrono::resetAll();
        $this->assertFalse(Peak\Common\Chrono::isOn('timer1'));
        $this->assertFalse(Peak\Common\Chrono::isCompleted('timer1'));
        
        Peak\Common\Chrono::start('timer1');
        Peak\Common\Chrono::stop('timer1');
        $this->assertTrue(Peak\Common\Chrono::isCompleted('timer1'));
    }
    	  
}