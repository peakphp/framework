<?php

namespace Peak\Climber\Commands;

use Peak\Climber\Cron\CronCommand;
use Peak\Common\TimeExpression;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CronAddCommand extends CronCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cron:add')

            // the short description shown while running "php bin/console list"
            ->setDescription('Install and/or check if cron tables are installed correctly.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This will install and/or check if cron tables are installed correctly.')

            ->setDefinition(
                new InputDefinition([
                    new InputOption('name', null, InputOption::VALUE_REQUIRED, 'Cron internal name'),
                    new InputOption('repeat', 'r', InputArgument::OPTIONAL,'Indicate if command should be repeatable (0=no, *=infinite, x=x times)'),
                    new InputOption('interval', 'i', InputOption::VALUE_REQUIRED, 'Indicate the interval in sec between repeat if apply. Format example: 600, , 2d, 3h, 20m, 2y'),
                    new InputOption('cmd', 'c', InputOption::VALUE_REQUIRED, 'The command to execute.'),
                ])
            );
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getOption('cmd');
        $repeat = $input->getOption('repeat');
        $interval = $input->getOption('interval');
        $name = $input->getOption('name');

        if (empty($command)) {
            return $output->writeln('<info>Command is missing (-c, --cmd)... </info>');
        }

        if (trim($repeat) === '' || strtolower($repeat) === 'no') {
            $repeat = -1;
        } elseif(strtolower($repeat) === 'yes') {
            $repeat = 0;
        } elseif(!is_numeric($repeat)) {
            return $output->writeln('[repeat] option value is invalid');
        }

        if (empty($interval) && $repeat != -1) {
            return $output->writeln('[interval] must be specified');
        }

        $next_execution = null;
        if(!empty($interval)) {
            $interval = (new TimeExpression($interval))->toSeconds();
            $next_execution = microtime() + $interval;
        }


        $final = [
            '`name`' => $name,
            '`cmd`' => $command,
            '`repeat`' => $repeat,
            '`interval`' => $interval,
            '`next_execution`' => $next_execution
        ];

        $this->conn->insert('climber_cron', $final);

        $output->writeln('Command #'.$this->conn->lastInsertId().' add to cron job!');
    }
}