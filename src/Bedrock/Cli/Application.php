<?php

declare(strict_types=1);

namespace Peak\Bedrock\Cli;

use Peak\Bedrock\AbstractApplication;
use Peak\Blueprint\Bedrock\CliApplication;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function is_array;
use function is_string;

class Application extends AbstractApplication implements CliApplication
{
    /**
     * @var \Symfony\Component\Console\Application
     */
    private $console;

    /**
     * Application constructor.
     * @param Kernel $kernel
     * @param Dictionary|null $props
     */
    public function __construct(Kernel $kernel, Dictionary $props = null)
    {
        $this->kernel = $kernel;
        $this->props = $props;

        $name = 'CliApplication';
        $version = '0.1';

        if (isset($props)) {
            $name = $props->get('name', $name);
            $version = $props->get('version', $version);
        }

        $this->console = new \Symfony\Component\Console\Application($name, $version);
    }

    /**
     * @return \Symfony\Component\Console\Application
     */
    public function console(): \Symfony\Component\Console\Application
    {
        return $this->console;
    }

    /**
     * @param $commands
     * @return $this|mixed
     * @throws \Exception
     */
    public function add($commands)
    {
        if (!is_array($commands)) {
            $commands = [$commands];
        }

        foreach ($commands as $command) {
            if (is_string($command)) {
                $command = $this->getContainer()->get($command);
            }
            if (!$command instanceof Command) {
                throw new \Exception('Invalid command');
            }
            $this->console->add($command);
        }
        return $this;
    }

    /**
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @return mixed
     * @throws \Exception
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        return $this->console->run($input, $output);
    }
}
