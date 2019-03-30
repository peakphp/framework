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
        $data = phpInput();
        $this->assertTrue(is_array($data));
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
     * test relativeBasePath()
     */
    function testRelativeBasePath()
    {
        $root = 'home/svr';
        $path = relativeBasePath($root.'/user/bin/test', $root);
        $this->assertTrue($path === '/user/bin');
        $this->assertTrue(relativeBasePath($root.'/user/bin/test') === 'home/svr/user/bin');
    }

    /**
     * test relativePath()
     */
    function testRelativePath()
    {
        $root = 'home/svr';
        $path = relativePath($root.'/user/bin/test', $root);
        $this->assertTrue($path === '/user/bin/test');
        $this->assertTrue(empty(relativePath($root.'/user/bin/test')));
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

    function testGetClassShortName()
    {
        $this->assertTrue(getClassShortName(\Peak\Collection\Collection::class) === 'Collection');
    }

    function testGetClassFilePath()
    {
        $this->assertTrue(!empty(getClassFilePath(\Peak\Collection\Collection::class)));
    }

    function testFileExpired()
    {
        $this->assertTrue(is_bool(fileExpired(getClassFilePath(\Peak\Collection\Collection::class), '2 day')));
    }

    function testCatchOutput()
    {
        $content = catchOutput(function() {
            echo 'test';
        });
        $this->assertTrue($content === 'test');
    }
}