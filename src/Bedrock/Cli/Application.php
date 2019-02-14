<?php

declare(strict_types=1);

namespace Peak\Bedrock\Cli;

use Peak\Blueprint\Bedrock\CliApplication;
use Peak\Blueprint\Bedrock\Kernel;
use Peak\Blueprint\Collection\Dictionary;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application implements CliApplication
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var \Symfony\Component\Console\Application
     */
    private $console;

    /**
     * @var Dictionary|null
     */
    private $props;

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
     * @return Kernel
     */
    public function getKernel(): Kernel
    {
        return $this->kernel;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->kernel->getContainer();
    }

    /**
     * @param string $property
     * @param mixed $default
     * @return mixed
     * @throws \Exception
     */
    public function getProp(string $property, $default = null)
    {
        if (!isset($this->props)) {
            throw new \Exception('Application properties is not defined! Cannot use getProp()');
        }
        return $this->props->get($property, $default);
    }

    /**
     * @param string $property
     * @return bool
     * @throws \Exception
     */
    public function hasProp(string $property): bool
    {
        if (!isset($this->props)) {
            throw new \Exception('Application properties is not defined! Cannot use hasProp()');
        }
        return $this->props->has($property);
    }

    /**
     * @return Dictionary|null
     */
    public function getProps(): ?Dictionary
    {
        return $this->props;
    }

    /**
     * @return \Symfony\Component\Console\Application
     */
    public function console(): \Symfony\Component\Console\Application
    {
        return $this->console;
    }

    /**
     * @param mixed|array<mixed> $commands
     * @return mixed|void
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
