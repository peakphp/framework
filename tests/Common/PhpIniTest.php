<?php

use PHPUnit\Framework\TestCase;
use \Peak\Common\PhpIni;

class PhpIniTest extends TestCase
{
    public function testSetIni()
    {
        $phpIni = new PhpIni([
            'memory_limit' => '129M'
        ]);

        $this->assertTrue(ini_get('memory_limit') === '129M');
    }

    public function testSetIniArray()
    {
        $phpIni = new PhpIni([
            'date' => [
                'timezone' => 'Toronto',
            ]
        ]);

        $this->assertFalse(ini_get('date.timezone') === 'America/Toronto');

        $phpIni = new PhpIni([
            'date' => [
                'timezone' => 'America/Toronto',
            ]
        ]);

        $this->assertTrue(ini_get('date.timezone') === 'America/Toronto');
    }

    public function testStrictException()
    {
        $this->expectException(\Exception::class);
        $phpIni = new PhpIni([
            'a' => 'a'
        ], true);
    }
}
