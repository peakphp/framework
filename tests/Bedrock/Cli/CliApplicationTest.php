<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Bedrock\Cli\Application;
use \Peak\Bedrock\Kernel;
use \Peak\Collection\PropertiesBag;
use \Psr\Container\ContainerInterface;
use \Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class CliApplicationTest extends TestCase
{
    protected function createApp($kernel = null, $props = null)
    {
        return new Application(
            $kernel ?? $this->createMock(Kernel::class),
            $props ?? null
        );
    }

    /**
     * Test class instantiation
     */
    public function testGeneral()
    {
        $app = $this->createApp(null, new PropertiesBag([
            'version' => '1.1'
        ]));

        $this->assertTrue($app->getProp('version') === '1.1');
        $this->assertInstanceOf(ContainerInterface::class, $app->getContainer());
        $this->assertInstanceOf(Kernel::class, $app->getKernel());

        $this->assertTrue($app->getProp('name') === null);
        $app->getProps()->set('name', 'Myapp');
        $this->assertTrue($app->getProp('name') === 'Myapp');
        $this->assertInstanceOf(\Symfony\Component\Console\Application::class, $app->console());
    }

    public function testAddCommand()
    {
        $app = $this->createApp(new Kernel('dev', new \Peak\Di\Container()), new PropertiesBag([
            'version' => '1.1'
        ]));
        $app->add(TestCliCommand::class);
        $app->add($this->createMock(TestCliCommand::class));
        $app->add([$this->createMock(TestCliCommand::class)]);
        $this->assertTrue($app->getProp('version') === '1.1');
    }

    public function testAddCommandException()
    {
        $this->expectException(Exception::class);
        $app = $this->createApp(new Kernel('dev', new \Peak\Di\Container()));
        $app->add($this->createMock(InvalidTestCliCommand::class));
    }

//    public function testRun()
//    {
//        $app = $this->createApp();
//        $result = $app->run(
//            $this->createMock(\Symfony\Component\Console\Input\InputInterface::class),
//            $this->createMock(\Symfony\Component\Console\Output\OutputInterface::class)
//        );
//        $this->assertTrue($result);
//    }

}

class TestCliCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        $this
            ->setName('example')
            ->setDescription('example command')
            ->setHelp('example help')
            ->setDefinition(new InputDefinition([
                    new InputOption('test', 't', InputOption::VALUE_NONE, 'test mode'),
                ])
            );
    }
}
class InvalidTestCliCommand {}
