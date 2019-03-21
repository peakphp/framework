<?php

use PHPUnit\Framework\TestCase;

class PhpIniTest extends TestCase
{
    public function testSetIni()
    {
        $phpIni = new \Peak\Common\PhpIni([
            'memory_limit' => '129M'
        ]);

        $this->assertTrue(ini_get('memory_limit') === '129M');
    }

    public function testStrictException()
    {
        $this->expectException(\Exception::class);
        $phpIni = new \Peak\Common\PhpIni([
            'a' => 'a'
        ], true);
    }
}
