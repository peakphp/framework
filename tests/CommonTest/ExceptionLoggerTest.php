<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\ExceptionLogger;

class ExceptionLoggerTest extends TestCase
{
    /**
     * test new instance
     */
    function testClass()
    {
        $file = __DIR__.'/error.log';
        $el = new ExceptionLogger(
            new \Exception('Message'),
            $file
        );

        $this->assertTrue(file_exists($file));
        //$this->assertTrue(is_writable(dirname($file)));
        unlink($file);
    }


}