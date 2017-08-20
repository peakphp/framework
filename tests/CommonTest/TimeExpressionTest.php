<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\TimeExpression;

class TimeExpressionTest extends TestCase
{
    function testExpression()
    {
        $this->assertTrue( (new TimeExpression(1))->toMicroseconds() === 1000);
        //$this->assertTrue( (new TimeExpression(1000))->toSeconds() === 1000);
        $this->assertTrue( (new TimeExpression("2day"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2 day"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2 day"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2d"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2 d"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("14 day"))->toSeconds() === 1209600);
        $this->assertTrue( (new TimeExpression("2 days"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2     days"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2 days 1 sec"))->toSeconds() === 172801);
        $this->assertTrue( (new TimeExpression("2day 15min"))->toSeconds() === 173700);
        $this->assertTrue( (new TimeExpression("15minutes2day"))->toSeconds() === 173700);
        $this->assertTrue( (new TimeExpression("4min30sec"))->toSeconds() === 270);
        $this->assertTrue( (new TimeExpression("4m30s"))->toSeconds() === 270);
        $this->assertTrue( (new TimeExpression("4m 30s"))->toSeconds() === 270);
        $this->assertTrue( (new TimeExpression("4mins"))->toMicroseconds() === 240000);
        $this->assertTrue( (new TimeExpression("4mins"))->toSeconds() === 240);
        $this->assertTrue( (new TimeExpression(3705))->toString() === '1 hour 1 minute 45 seconds');

    }

    function testClockFormat()
    {
        $this->assertTrue( (new TimeExpression('02:40:40'))->toSeconds() == 9640);
        $this->assertTrue( (new TimeExpression('4:30'))->toSeconds() == 270);
        $this->assertTrue( (new TimeExpression('4:30'))->toString() == '4 minutes 30 seconds');
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
        $datetime->add((new TimeExpression('25 mins 15 secs'))->toDateInterval());
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

