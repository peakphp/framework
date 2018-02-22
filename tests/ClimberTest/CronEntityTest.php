<?php

use PHPUnit\Framework\TestCase;

use Peak\Climber\Cron\CronEntity;

class CronEntityTest extends TestCase
{

    public function testBasics()
    {
        $entity = new CronEntity([
            'id' => 12,
            'name' => 'test',
            'cmd' => 'dir',
            'sys_smd' => 0,
            'repeat' => 0,
            'status' => null,
            'in_action' => 0,
            'error' => '',
            'interval' => 123,
            'last_execution' => 123413434,
            'next_execution' => 123413434,
            'enabled' => 1
        ]);

        //echo $entity->repeat;

        $this->assertTrue($entity->name === 'test');
        //->assertTrue($entity->repeat === 'test');
    }

    /**
     * Test repeat
     */
    public function testRepeat()
    {
        $entity = new CronEntity(['repeat' => 0]);

        $this->assertTrue($entity->repeat === 'always');
        $this->assertTrue($entity->raw('repeat') == 0);

        $entity = new CronEntity(['repeat' => 1]);

        $this->assertTrue($entity->repeat === '1 time');
        $this->assertTrue($entity->raw('repeat') == 1);

        $entity = new CronEntity(['repeat' => -1]);

        $this->assertTrue($entity->repeat === 'no');
        $this->assertTrue($entity->raw('repeat') == -1);

        $entity = new CronEntity(['repeat' => 2]);

        $this->assertTrue($entity->repeat === '2 times');
        $this->assertTrue($entity->raw('repeat') == 2);
    }

    /**
     * Test interval
     */
    public function testInterval()
    {
        $entity = new CronEntity(['interval' => 0]);
        $this->assertTrue(empty($entity->interval));
        $this->assertTrue($entity->raw('interval') == 0);

        $entity = new CronEntity(['interval' => 1]);
        $this->assertTrue($entity->interval === '1 second');
        $this->assertTrue($entity->raw('interval') == 1);

        $entity = new CronEntity(['interval' => 2]);
        $this->assertTrue($entity->interval === '2 seconds');

        $entity = new CronEntity(['interval' => 300]);
        $this->assertTrue($entity->interval === '5 minutes');
    }

    /**
     * Test last and next execution
     */
    public function testLastNextExecution()
    {
        $time = time();
        $datetime = date('Y-m-d H:i:s', $time);

        $entity = new CronEntity(['last_execution' => $time, 'next_execution' => $time]);

        $this->assertTrue($entity->last_execution === $datetime);
        $this->assertTrue($entity->raw('last_execution') == $time);
        $this->assertTrue($entity->next_execution === $datetime);
        $this->assertTrue($entity->raw('next_execution') == $time);
    }

    /**
     * Test enabled
     */
    public function testEnabled()
    {
        $entity = new CronEntity(['enabled' => false]);
        $this->assertTrue($entity->enabled === 'no');
        $this->assertTrue($entity->raw('enabled') === false);

        $entity = new CronEntity(['enabled' => true]);
        $this->assertTrue($entity->enabled === 'yes');
        $this->assertTrue($entity->raw('enabled') === true);
    }

    /**
     * Test syscmd
     */
    public function testSysCmd()
    {
        $entity = new CronEntity(['sys_cmd' => false]);
        $this->assertTrue($entity->sys_cmd === 'no');
        $this->assertTrue($entity->raw('sys_cmd') === false);

        $entity = new CronEntity(['sys_cmd' => true]);
        $this->assertTrue($entity->sys_cmd === 'yes');
        $this->assertTrue($entity->raw('sys_cmd') === true);
    }

    /**
     * Test status
     */
    public function testStatus()
    {
        $entity = new CronEntity(['status' => null]);
        $this->assertTrue($entity->status === 'n/a');
        $this->assertTrue($entity->raw('status') === null);

        $entity = new CronEntity(['status' => 0]);
        $this->assertTrue($entity->status === 'fail');
        $this->assertTrue($entity->raw('status') == 0);

        $entity = new CronEntity(['status' => 1]);
        $this->assertTrue($entity->status === 'success');
        $this->assertTrue($entity->raw('status') == 1);
    }


}