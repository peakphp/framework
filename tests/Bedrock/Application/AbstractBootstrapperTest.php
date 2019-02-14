<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Kernel;
use \Peak\Bedrock\Application\Application;
use \Peak\Di\Container;
use \Peak\Http\Request\HandlerResolver;

require_once FIXTURES_PATH . '/application/Bootstrapper.php';

/**
 * Class AbstractBootstrapperTest
 */
class AbstractBootstrapperTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreate()
    {
        $kernel = new Kernel('dev', new Container);
        $handlerResolver = $this->createMock(HandlerResolver::class);
        $app = new Application($kernel, $handlerResolver);
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
