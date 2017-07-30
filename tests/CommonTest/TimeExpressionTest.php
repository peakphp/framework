<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\TimeExpression;

class TimeExpressionTest extends TestCase
{
    function testExpression()
    {
        $this->assertTrue( (new TimeExpression(1))->toMicroseconds() === 1000);
        $this->assertTrue( (new TimeExpression(1000))->toSeconds() === 1000);
        $this->assertTrue( (new TimeExpression("2d"))->toSeconds() === 172800);
        $this->assertTrue( (new TimeExpression("2d15min"))->toSeconds() === 173700);
        $this->assertTrue( (new TimeExpression("2d 15min"))->toSeconds() === 173700);
        $this->assertTrue( (new TimeExpression("15min2d"))->toSeconds() === 173700);

        $this->assertTrue( (new TimeExpression("4min30sec"))->toSeconds() === 270);

        $this->assertTrue((new TimeExpression(86400 + 43200))->toString() === '1day 12hours');
        $this->assertTrue((new TimeExpression(86400 + 43199))->toString() === '1day 11hours 59mins 59secs');
        $this->assertTrue((new TimeExpression(1296000))->toString() === '15days');

        $this->assertTrue((new TimeExpression(60.50001))->toString() === '1min 500ms');
        $this->assertTrue((new TimeExpression(60.50001))->toString('%d %s') === '1 min 500 ms');
    }
}