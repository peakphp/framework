<?php
use PHPUnit\Framework\TestCase;

use Peak\Common\Session;

class ConfigSessionTest extends TestCase
{
    function testCreateObject()
    {
        $session =  new Session();
    }

}