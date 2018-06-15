<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\TimeExpression;

class TimeExpressionTest extends TestCase
{
    function testExpression()
    {
//        echo "\n";
//        echo (new TimeExpression(86400))->toString();
//        echo "\n";
//
//        echo "\n";
//        echo (new TimeExpression(59))->toString();
//        echo "\n";
//
//        echo "\n";
//        echo (new TimeExpression('30d'))->toString();
//        echo (new TimeExpression(0))->toString('%h hour(s)');
//        die();
//        echo "\n";
//        echo (new TimeExpression('31d'))->toString();
//        echo "\n";
//        echo (new TimeExpression('32d'))->toString('%d day');
//        echo "\n"; die();

        $this->assertTrue( (new TimeExpression('30d'))->toString() === '1 month');
        $this->assertTrue( (new TimeExpression('31d'))->toString() === '1 month 1 day');
        $this->assertTrue( (new TimeExpression(86400))->toString() === '1 day');
        $this->assertTrue( (new TimeExpression(86400*2))->toString() === '2 days');
        $this->assertTrue( (new TimeExpression(86400*3))->toString() === '3 days');
        $this->assertTrue( (new TimeExpression(86400*4))->toString() === '4 days');
        $this->assertTrue( (new TimeExpression(86400*5))->toString() === '5 days');
        $this->assertTrue( (new TimeExpression(86400*6))->toString() === '6 days');
        $this->assertTrue( (new TimeExpression(86400*7))->toString() === '7 days');
        $this->assertTrue( (new TimeExpression(86400*30))->toString() === '1 month');
        $this->assertTrue( (new TimeExpression(86400*60))->toString() === '2 months');
        $this->assertTrue( (new TimeExpression(86400*365))->toString() === '1 year');
        $this->assertTrue( (new TimeExpression(86400*365*2))->toString() === '2 years');

        $this->assertTrue( (new TimeExpression(1))->toMicroseconds() === 1000);
        $this->assertTrue( (new TimeExpression("2day"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2 day"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2 days"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2d"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2 d"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("1 day"))->toSeconds() === 86400);
        $this->assertTrue( (new TimeExpression("2     days"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2hour"))->toSeconds() === 7200);
        $this->assertTrue( (new TimeExpression("14 day"))->toSeconds() === 1209600);
        $this->assertTrue( (new TimeExpression("2 days 1 sec"))->toSeconds() === 172801);
        $this->assertTrue( (new TimeExpression("2day 15min"))->toSeconds() === 173700);
        $this->assertTrue( (new TimeExpression("15minutes2day"))->toSeconds() === 173700);
        $this->assertTrue( (new TimeExpression("4min30sec"))->toSeconds() === 270);
        $this->assertTrue( (new TimeExpression("4m30s"))->toSeconds() === 270);
        $this->assertTrue( (new TimeExpression("4m 30s"))->toSeconds() === 270);
        $this->assertTrue( (new TimeExpression("4mins"))->toMicroseconds() === 240000);
        $this->assertTrue( (new TimeExpression("4mins"))->toSeconds() === 240);
        $this->assertTrue( (new TimeExpression(3705))->toString() === '1 hour 1 minute 45 seconds');
        $this->assertTrue( (new TimeExpression(0.10))->toString() === '100 milliseconds');
    }

    function testClockFormat()
    {
        //echo (new TimeExpression('02:40:40'))->toClockString();
//        echo (new TimeExpression(1))->toClockString()."\n";
//        echo (new TimeExpression('02:40:40'))->toClockString()."\n";
//        echo (new TimeExpression('02:00:40'))->toClockString()."\n";
//        echo (new TimeExpression('1 day 36 hour'))->toClockString()."\n";
//        echo (new TimeExpression(65048))->toClockString()."\n";
        $this->assertTrue( (new TimeExpression('4h 36m 21s'))->toClockString() == '04:36:21');
        $this->assertTrue( (new TimeExpression('02:40:40'))->toClockString() == '02:40:40');
        $this->assertTrue( (new TimeExpression('02:00:40'))->toClockString() == '02:00:40');
        $this->assertTrue( (new TimeExpression("1day"))->toClockString() == '24:00:00');
        $this->assertTrue( (new TimeExpression("3day"))->toClockString() == '72:00:00');
        $this->assertTrue( (new TimeExpression("1min"))->toClockString() == '00:01:00');
        $this->assertTrue( (new TimeExpression("1 day 36 hour"))->toClockString() == '60:00:00');
        $this->assertTrue( (new TimeExpression(12445))->toClockString() == '03:27:25');
        $this->assertTrue( (new TimeExpression("1min"))->toClockString(true) == '01:00');
        $this->assertTrue( (new TimeExpression("1 day 36 hour"))->toClockString(true) == '60:00:00');
    }

    function testEmpty()
    {
        // 0 or empty
        $this->assertTrue( (new TimeExpression(0))->toSeconds() == 0);
        $this->assertTrue( (new TimeExpression(0))->toString() === '0 second');
        $this->assertTrue( (new TimeExpression(0))->toString('%h hour(s)') === '0 hour(s)');
        $this->assertTrue( (new TimeExpression(''))->toString('%h hour(s)') === '0 hour(s)');
        $this->assertTrue( (new TimeExpression(''))->toSeconds() == 0);
    }

    function testIntervalSpec()
    {
        // using DateInterval syntax (ISO8601 interval spec)
        $this->assertTrue( (new TimeExpression("PT1H35M45S"))->toSeconds() === 5745);
        $this->assertTrue( (new TimeExpression("P2D"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("P2DT1M15S"))->toSeconds() === 172875);
        $this->assertTrue( (new TimeExpression("P2DT1M15S"))->toString() === '2 days 1 minute 15 seconds');
        $this->assertTrue( (new TimeExpression("P2W"))->toString() === '14 days');
        $this->assertTrue( (new TimeExpression("P2WT10H"))->toString() === "14 days 10 hours");

        // using DateInterval syntax (ISO8601 interval spec) with custom format
        $this->assertTrue(
            (new TimeExpression("P3DT5H"))->toString('%d jours %h heures') === '3 jours 5 heures'
        );
        $this->assertTrue(
            (new TimeExpression("P3DT5H"))->toString('%y years %d days %h hours') === '0 years 3 days 5 hours'
        );

        // getting ISO8601 interval spec
//        echo (new TimeExpression('2 day 1 sec'))->toIntervalSpec();
        $this->assertTrue( (new TimeExpression('2 days 1 sec'))->toIntervalSpec() === 'P2DT1S');

        // getting ISO8601 interval spec
        $this->assertTrue( (new TimeExpression(1500))->toIntervalSpec() === 'PT25M');

        // getting ISO8601 interval spec
        $this->assertTrue( (new TimeExpression("2 week 3 days"))->toIntervalSpec() === 'P17D');

        $this->assertTrue(TimeExpression::dateIntervalToIntervalSpec(new DateInterval('P17D')) === 'P17D');
    }

    function testDiff()
    {
        // using TimeExpression to add time from a DateTime
        $datetime = new DateTime('2017-08-14 11:00:00');
        $dtToAdd = (new TimeExpression('25 mins 15 secs'))->toDateInterval();
        $datetime->add($dtToAdd);
        $this->assertTrue($datetime->format('Y-m-d H:i:s') === '2017-08-14 11:25:15');
    }

    function testIsValid()
    {
        $this->assertTrue(TimeExpression::isValid('25 mins 15 secs'));
        $this->assertTrue(TimeExpression::isValid('P2WT10H'));
        $this->assertTrue(TimeExpression::isValid('4m 30s'));
        $this->assertTrue(TimeExpression::isValid('12:20:15'));
        $this->assertTrue(TimeExpression::isValid(234234));

        $this->assertFalse(TimeExpression::isValid('asdas'));
    }

    function testCreateFrom()
    {
        $this->assertTrue(TimeExpression::createFrom('25 mins 15 secs') instanceof TimeExpression);
        $this->assertFalse(TimeExpression::createFrom('asdas'));
        $this->assertFalse(($ti = TimeExpression::createFrom('asdas')) !== false);
        echo "\n". TimeExpression::createFrom('2y 2w 35s')->toSeconds()."\n";
        $this->assertTrue(($ti = TimeExpression::createFrom('2y 2w 7.2 hour ... 35sec ')) !== false);
    }

    function testException()
    {
        try {
            (new TimeExpression("unknow"));
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }
}

