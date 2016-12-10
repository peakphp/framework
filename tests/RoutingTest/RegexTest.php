<?php
use PHPUnit\Framework\TestCase;

use Peak\Routing\Regex;

class RegexTest extends TestCase
{

    public function testQuickRegex()
    {   
        $regex = Regex::build(':any');
        $this->assertTrue($regex === '[^\/]+');

        $regex = Regex::build(':potatoe');
        $this->assertTrue($regex === ':potatoe');

        $regex = Regex::build('[12][0-9]{3}');
        $this->assertTrue($regex === '[12][0-9]{3}');
    }

    public function testAddRegex()
    {   
        Regex::addQuickRegex('prefix', '([wx])([yz])');
        $regex = Regex::build(':prefix');
        $this->assertTrue($regex === '([wx])([yz])');
    }

}