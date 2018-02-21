<?php

namespace Peak\Climber\Commands;

use Peak\Bedrock\Application;
use Peak\Climber\Cron\CronCommand;
use Peak\Climber\Cron\OptionFormat;
use Peak\Common\TimeExpression;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClimberCronAddCommand extends CronCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName($this->prefix.':add')

            // the short description shown while running "php bin/console list"
            ->setDescription('Add cron job')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This will create a cron job')

            ->setDefinition(
                new InputDefinition([
                    new InputOption('name', null, InputOption::VALUE_REQUIRED, 'Cron internal name'),
                    new InputOption('sys', 's', InputOption::VALUE_NONE, 'Cron command is a system command'),
                    new InputOption('repeat', 'r', InputOption::VALUE_OPTIONAL, 'Indicate if command should be repeatable', '-1'),
                    new InputOption('interval', 'i', InputOption::VALUE_REQUIRED, 'Indicate the interval in second between repetition if apply.'),
                    new InputOption('cmd', 'c', InputOption::VALUE_REQUIRED, 'The command to execute.'),
                    new InputOption('initial-delay', 'd', InputOption::VALUE_REQUIRED, 'Initial delay (this delay is added to cronjob next_execution field', 0),
                ])
            );
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getOption('cmd');
        $repeat = $input->getOption('repeat');
        $interval = $input->getOption('interval');
        $name = $input->getOption('name');
        $sys_cmd = $input->getOption('sys');
        $delay = $input->getOption('initial-delay');

        if (empty($command)) {
            return $output->writeln('<error>Command is missing (-c, --cmd)... </error>');
        }

        if ($repeat !== null) {
            // evaluate -r value
            if (!OptionFormat::repeatValid($repeat)) {
                return $output->writeln('[-r|--repeat] option value is invalid');
            }
            $repeat = OptionFormat::repeat($repeat);
        } elseif ($repeat === null) {
            // -r flag is passed without value
            $repeat = 0;
        }

        // if cron job is repeatable, an interval must be specify
        if ($repeat != -1 && empty($interval)) {
            return $output->writeln('[-i|--interval] must be specified');
        }

        // if flag -s is no specified, nullify system command field
        if (!$sys_cmd) {
            $sys_cmd = null;
        }

        $interval_sec = (new TimeExpression($interval))->toSeconds();

        // handle interval as time expression
        $next_execution = time();
        if (!empty($interval)) {
            $next_execution += $interval_sec + (new TimeExpression($delay))->toSeconds();
        }

        // final array to insert into database
        $final = [
            '`name`' => $name,
            '`cmd`' => $command,
            '`sys_cmd`' => $sys_cmd,
            '`repeat`' => $repeat,
            '`interval`' => $interval_sec,
            '`next_execution`' => $next_execution
        ];

        $this->conn->insert('climber_cron', $final);

        $output->writeln('Cron job #'.$this->conn->lastInsertId().' added!');

        if ($repeat == 0) {
            $output->writeln('This cron job will be executed indefinitely at interval of '.(new TimeExpression($interval))->toString());
        } elseif ($repeat > 0) {
            $output->writeln('This cron job will be executed '.$repeat.' time(s) at interval of '.(new TimeExpression($interval))->toString());
        }

        $output->writeln('Next execution is planned at '.(date('Y-m-d H:i:s', $next_execution)));
    }
}
