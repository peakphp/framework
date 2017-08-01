<?php

namespace Peak\Climber\Commands;

use Peak\Climber\Cron\CronCommand;
use Peak\Climber\Cron\Executor;
use Peak\Config\ConfigLoader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use \Exception;

class ClimberCronRunCommand extends CronCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName($this->prefix.':run')

            // the short description shown while running "php bin/console list"
            ->setDescription('Run cron jobs')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This will run all cron jobs applicable at the moment')

            ->setDefinition(
                new InputDefinition([
                    new InputArgument('config', InputArgument::REQUIRED, 'Configuration file path')
                ])
            );
    }

    /**
     * Process command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('config');

        try {
            $config = (new ConfigLoader([$file]))->asArray();
        } catch (\Exception $e) {
            return $output->writeln($e->getMessage());
        }

        $config = $this->propagateEnv($config);

        try {
            // create cli application for cron
            (new Executor(null, $config))->run();
        } catch(Exception $e) {
            echo '['.get_class($e).']:'."\n".$e->getMessage();
        }
    }


    /**
     * Propagate current environment to cron run config. Recursive
     *
     * @param $config
     * @return mixed
     */
    protected function propagateEnv($config)
    {
        foreach ($config as $index => $c) {
            if (is_array($c)) {
                $config[$index] = $this->propagateEnv($c);
            } else {
                $config[$index] = str_replace('%env%', APPLICATION_ENV, $c);
            }
        }

        return $config;
    }
}
