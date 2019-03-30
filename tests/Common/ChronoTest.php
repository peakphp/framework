<?php

use PHPUnit\Framework\TestCase;

use \Peak\Common\Chrono\Chrono;

/**
 * @package    Peak\Common\Chrono
 */
class ChronoTest extends TestCase
{
	public function testName()
    {
        $chrono = new Chrono();
        $this->assertTrue(empty($chrono->getName()));

        $chrono = new Chrono('mychrono');
        $this->assertTrue($chrono->getName() === 'mychrono');
    }

    public function testStop()
    {
        $chrono = new Chrono();
        $this->assertFalse($chrono->isStopped());
        $chrono->stop();
        $this->assertTrue($chrono->isStopped());
        $chrono->stop();
    }

    public function testGetSec()
    {
        $epsilon = 0.00001;
        $chrono = new Chrono();
        usleep(100);

        $sec = $chrono->getSec(4);
        $this->assertTrue($sec < 1);
        $this->assertTrue(abs($sec) > $epsilon);
    }

    public function testGetMs()
    {
        $epsilon = 0.00001;
        $chrono = new Chrono();
        usleep(100);

        $ms = $chrono->getMs(4);
        $this->assertTrue($ms < 1000);
        $this->assertTrue(abs($ms) > $epsilon);
    }

    public function testMarkStep()
    {
        $chrono = new Chrono();
        usleep(2);
        $chrono->markStep('first-step');
        $chrono->markStep('second-step');
        usleep(50);
        $chrono->markStep('third-step');
        $steps = $chrono->getSteps();
        $this->assertTrue(count($steps) == 3);
        $this->assertTrue($steps[1]->getSec() == 0);
        $this->assertTrue($steps[1]->getMs() > 0);
        $this->assertTrue($steps[1]->getId() === 'second-step');
        $this->assertTrue($steps[1]->getDescription() === '');
    }
}