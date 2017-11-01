<?php

use PHPUnit\Framework\TestCase;

use Peak\Climber\Cron\CronBuilder;

class CronBuilderTest extends TestCase
{

    public function atestBuild()
    {
        $cron = (new CronBuilder())
            ->name('test1')
            ->cmd('dir')
            ->repeat(99)
            ->enabled('yes')
            ->sysCmd('yes')
            ->interval('5min')
            ->build();

        print_r($cron);
        $this->assertTrue(is_array($cron));
        $this->assertTrue($cron['cmd'] === 'dir');
        $this->assertTrue($cron['repeat'] == 99);
        $this->assertTrue($cron['enabled'] == 1);
        $this->assertTrue($cron['interval'] == 300);
        $this->assertTrue($cron['sys_cmd'] == '1');
    }

    public function testExceptions()
    {
        $error = false;
        try {
            $cron = (new CronBuilder())
                ->build();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        $this->assertTrue($error === 'Cron command is empty');

        try {
            $cron = (new CronBuilder())
                ->cmd('dir')
                ->repeat('0')
                ->build();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        $this->assertTrue($error === 'Cron interval time must be defined when repeat option is on');
    }

}