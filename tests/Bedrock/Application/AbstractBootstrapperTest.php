<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Kernel;
use \Peak\Bedrock\Application\Application;
use \Peak\Bedrock\Application\AbstractBootstrapper;

require_once FIXTURES_PATH . '/application/Bootstrapper.php';

/**
 * Class AbstractBootstrapperTest
 */
class AbstractBootstrapperTest extends TestCase
{
    public function testCreate()
    {
        $app = $this->createMock(Application::class);

        $kernel = $this->createMock(Kernel::class);
        $kernel->expects($this->once())
            ->method('getEnv')
            ->will($this->returnValue('dev'));

        $app->expects($this->once())
            ->method('getKernel')
            ->will($this->returnValue($kernel));

        $bootstrapper = new Bootstrapper($app);

        $this->assertTrue(empty($_GET));
        $this->assertTrue($bootstrapper->i === 0);
        $this->assertTrue($bootstrapper->j === 0);
        $bootstrapper->boot();
        $this->assertTrue($bootstrapper->i === 1);
        $this->assertTrue($bootstrapper->j === 1);
        $this->assertTrue(isset($_GET['BootstrapProcess']));
        $_GET = [];
    }
}
