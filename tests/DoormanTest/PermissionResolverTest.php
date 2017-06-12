<?php
use PHPUnit\Framework\TestCase;

use Peak\Doorman\PermissionResolver;


class PermissionResolverTest extends TestCase
{

    function testDecimal()
    {

        $perm = (new PermissionResolver(0))->get();
        $this->assertTrue($perm == 0);

        $perm = (new PermissionResolver(1))->get();
        $this->assertTrue($perm == 1);

        $perm = (new PermissionResolver(2))->get();
        $this->assertTrue($perm == 2);

        $perm = (new PermissionResolver(3))->get();
        $this->assertTrue($perm == 3);

        $perm = (new PermissionResolver(4))->get();
        $this->assertTrue($perm == 4);

        $perm = (new PermissionResolver(5))->get();
        $this->assertTrue($perm == 5);

        $perm = (new PermissionResolver(6))->get();
        $this->assertTrue($perm == 6);
        
        $perm = (new PermissionResolver(7))->get();
        $this->assertTrue($perm == 7);
    }

    function testDecimalAsString()
    {

        $perm = (new PermissionResolver('0'))->get();
        $this->assertTrue($perm == 0);

        $perm = (new PermissionResolver('1'))->get();
        $this->assertTrue($perm == 1);

        $perm = (new PermissionResolver('2'))->get();
        $this->assertTrue($perm == 2);

        $perm = (new PermissionResolver('3'))->get();
        $this->assertTrue($perm == 3);

        $perm = (new PermissionResolver('4'))->get();
        $this->assertTrue($perm == 4);

        $perm = (new PermissionResolver('5'))->get();
        $this->assertTrue($perm == 5);

        $perm = (new PermissionResolver('6'))->get();
        $this->assertTrue($perm == 6);
        
        $perm = (new PermissionResolver('7'))->get();
        $this->assertTrue($perm == 7);
    }

    function testAlphaNum()
    {

        $perm = (new PermissionResolver('---'))->get();
        $this->assertTrue($perm == 0);

        $perm = (new PermissionResolver('--x'))->get();
        $this->assertTrue($perm == 1);

        $perm = (new PermissionResolver('-w-'))->get();
        $this->assertTrue($perm == 2);

        $perm = (new PermissionResolver('-wx'))->get();
        $this->assertTrue($perm == 3);

        $perm = (new PermissionResolver('r--'))->get();
        $this->assertTrue($perm == 4);

        $perm = (new PermissionResolver('r-x'))->get();
        $this->assertTrue($perm == 5);

        $perm = (new PermissionResolver('rw-'))->get();
        $this->assertTrue($perm == 6);
        
        $perm = (new PermissionResolver('rwx'))->get();
        $this->assertTrue($perm == 7);
    }

    function testBinary()
    {

        $perm = (new PermissionResolver('000'))->get();
        $this->assertTrue($perm == 0);

        $perm = (new PermissionResolver('001'))->get();
        $this->assertTrue($perm == 1);

        $perm = (new PermissionResolver('010'))->get();
        $this->assertTrue($perm == 2);

        $perm = (new PermissionResolver('011'))->get();
        $this->assertTrue($perm == 3);

        $perm = (new PermissionResolver('100'))->get();
        $this->assertTrue($perm == 4);

        $perm = (new PermissionResolver('101'))->get();
        $this->assertTrue($perm == 5);

        $perm = (new PermissionResolver('110'))->get();
        $this->assertTrue($perm == 6);
        
        $perm = (new PermissionResolver('111'))->get();
        $this->assertTrue($perm == 7);
    }

    function testText()
    {

        $perm = (new PermissionResolver('No Permission'))->get();
        $this->assertTrue($perm == 0);

        $perm = (new PermissionResolver('Execute'))->get();
        $this->assertTrue($perm == 1);

        $perm = (new PermissionResolver('Write'))->get();
        $this->assertTrue($perm == 2);

        $perm = (new PermissionResolver('Write + Execute'))->get();
        $this->assertTrue($perm == 3);

        $perm = (new PermissionResolver('Read'))->get();
        $this->assertTrue($perm == 4);

        $perm = (new PermissionResolver('Read + Execute'))->get();
        $this->assertTrue($perm == 5);

        $perm = (new PermissionResolver('Read + Write'))->get();
        $this->assertTrue($perm == 6);
        
        $perm = (new PermissionResolver('Read + Write + Execute'))->get();
        $this->assertTrue($perm == 7);
    }

    /**
     * Test exception
     */
    function testExceptions()
    {
        try {
            $perm = (new PermissionResolver('Unknown'))->get();
        } catch (Exception $e) {
            $error1 = true;
        }
        $this->assertTrue(isset($error1));

        try {
            $perm = (new PermissionResolver(-1))->get();
        } catch (Exception $e) {
            $error2 = true;
        }
        $this->assertTrue(isset($error2));

        try {
            $perm = (new PermissionResolver(8))->get();
        } catch (Exception $e) {
            $error3 = true;
        }
        $this->assertTrue(isset($error3));
    }

}