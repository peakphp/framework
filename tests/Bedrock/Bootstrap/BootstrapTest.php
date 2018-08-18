<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Bootstrap\Bootstrap;

require_once FIXTURES_PATH.'/application/BootstrapProcess.php';

/**
 * Class BootstrapTest
 */
class BootstrapTest extends TestCase
{
    public function testBootWithClassName()
    {
        $this->assertFalse(isset($_GET['BootstrapProcess']));
        $bootstrap = new Bootstrap([BootstrapProcess::class]);
        $this->assertTrue($bootstrap->boot());
        $this->assertTrue(isset($_GET['BootstrapProcess']));
        $this->assertTrue($_GET['BootstrapProcess'] === BootstrapProcess::class);
        $_GET = [];
    }

    public function testBootWithBootableInstance()
    {
        $this->assertFalse(isset($_GET['BootstrapProcess']));
        $bootstrap = new Bootstrap([new BootstrapProcess()]);
        $this->assertTrue($bootstrap->boot());
        $this->assertTrue(isset($_GET['BootstrapProcess']));
        $this->assertTrue($_GET['BootstrapProcess'] === BootstrapProcess::class);
        $_GET = [];
    }

    public function testBootWithClosure()
    {
        $this->assertFalse(isset($_GET['CallableTest']));
        $bootstrap = new Bootstrap([
            function() {
                $_GET['CallableTest'] = 'foobar';
            }
        ]);
        $this->assertTrue($bootstrap->boot());
        $this->assertTrue(isset($_GET['CallableTest']));
        $this->assertTrue($_GET['CallableTest'] === 'foobar');
        $_GET = [];
    }

    /**
     * @expectedException \Peak\Bedrock\Bootstrap\Exception\InvalidBootableProcessException
     */
    public function testInvalidBootableProcessException()
    {
        $bootstrap = new Bootstrap(['foobar']);
        $bootstrap->boot();
    }

}
