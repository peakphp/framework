<?php

namespace Peak\Climber\Commands;

use Peak\Climber\Application;
use Peak\Climber\Cron\Cron;
use Peak\Climber\Cron\CronCommand;
use Peak\Climber\Cron\InstallDatabase;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CronInstallCommand extends CronCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cron:install')

            // the short description shown while running "php bin/console list"
            ->setDescription('Install and/or check if cron tables are installed correctly.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This will install and/or check if cron tables are installed correctly.')

            ->setDefinition(
                new InputDefinition([
                    new InputOption('reinstall', 'r', InputOption::VALUE_NONE, 'Force re-installation if tables exists'),
                ])
            );
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
        $reinstall = $input->getOption('reinstall');

        if ($reinstall) {
            $this->conn->executeQuery('DROP TABLE IF EXISTS climber_cron');
            $this->conn->executeQuery('DROP TABLE IF EXISTS climber_console');
        }

        if (!Cron::isInstalled($this->conn)) {
            $output->writeln('Installing cron system...');
            new InstallDatabase($this->conn, Application::conf('cron.db.driver'));
            return $output->writeln('Done!');
        }

        $output->writeln('Cron system is already installed...');
    }
}
