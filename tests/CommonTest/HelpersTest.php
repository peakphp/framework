<?php

use PHPUnit\Framework\TestCase;

/**
 * Test helpers.php functions
 */
class HelpersTest extends TestCase
{
    /**
     * test exceptionTrace()
     */
    function testExceptionTrace()
    {
        $content1 = exceptionTrace(new \Exception('Message'));
        $this->assertTrue(mb_strlen($content1) > 1);

        $content2 = exceptionTrace(new \Peak\Common\DataException('Message', ['misc_data']));
        $this->assertTrue(mb_strlen($content2) > 1);
        $this->assertTrue(mb_strlen($content2) > mb_strlen($content1));
    }

    /**
     * test printExceptionTrace()
     */
    function testPrintExceptionTrace()
    {
        ob_start();
        printExceptionTrace(new \Exception('Message'));
        $content = ob_get_clean();
        $this->assertTrue(mb_strlen($content) > 1);
    }

    /**
     * test printHtmlExceptionTrace()
     */
    function testPrintHtmlExceptionTrace()
    {
        ob_start();
        printHtmlExceptionTrace(new \Exception('Message'));
        $content = ob_get_clean();
        $this->assertTrue(mb_strlen($content) > 1);
    }


    /**
     * test phpinput()
     */
    function testPhpinput()
    {
        $data = phpinput();
        $this->assertTrue($data instanceof Peak\Common\Collection\Collection);
    }

    /**
     * test phpShowAllErrors()
     */
    function testShowAllErrors()
    {
        ini_set('display_errors', 0);
        $this->assertTrue(ini_get('display_errors') == 0);
        showAllErrors();
        $this->assertTrue(ini_get('display_errors') == 1);
    }

    /**
     * test isCli()
     */
    function testIsCli()
    {
        $this->assertTrue(isCli());
    }

    /**
     * test relativeBasepath()
     */
    function testRelativeBasepath()
    {
        $root = 'home/svr';
        $path = relativeBasepath($root.'/user/bin/test', $root);
        $this->assertTrue($path === '/user/bin');
    }

    /**
     * test relativePath()
     */
    function testRelativePath()
    {
        $root = 'home/svr';
        $path = relativePath($root.'/user/bin/test', $root);
        $this->assertTrue($path === '/user/bin/test');
    }

    /**
     * test formatSize()
     */
    function testFormatFileSize()
    {
        $size = '1234';
        $fsize = formatSize($size);
        $this->assertTrue($fsize === '1.21 kB');

        $size = 0;
        $fsize = formatSize($size);
        $this->assertTrue($fsize === '0 kB');
    }

    /**
     * test interpolate()
     */
    function testInterpolate()
    {
        $message = 'User {username} created';
        $context = ['username' => 'foobar'];
        $final = interpolate($message, $context);

        $this->assertTrue($final === 'User foobar created');

        $final = interpolate($message, $context, function($val) {
            return strtoupper($val);
        });

        $this->assertTrue($final === 'User FOOBAR created');
    }
}