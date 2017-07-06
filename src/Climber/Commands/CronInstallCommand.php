<?php

namespace Peak\Climber\Commands;

use Peak\Climber\Application;
use Peak\Climber\Cron\CronCommand;
use Peak\Climber\Cron\InstallDatabase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronInstallCommand extends CronCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cron:install')

            // the short description shown while running "php bin/console list"
            ->setDescription('Install and/or check if cron tables are installed correctly.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This will install and/or check if cron tables are installed correctly.');
    }

    /**
     * Check and install cron system if apply
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isInstalled()) {
            $output->writeln('Installing cron system...');
            new InstallDatabase($this->conn, Application::conf('crondb.driver'));
            return $output->writeln('Done!');
        }

        $output->writeln('Cron system is already installed...');
    }
}