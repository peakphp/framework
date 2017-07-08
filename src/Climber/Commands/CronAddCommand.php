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
                    new InputOption('sys', 's', InputOption::VALUE_NONE, 'Cron command is a system command'),
                    new InputOption('repeat', 'r', InputOption::VALUE_OPTIONAL,'Indicate if command should be repeatable (0=no, *=infinite, x=x times)'),
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
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getOption('cmd');
        $repeat = trim(strtolower($input->getOption('repeat')));
        $interval = $input->getOption('interval');
        $name = $input->getOption('name');
        $sys_cmd = $input->getOption('sys');
        
        if (empty($command)) {
            return $output->writeln('<info>Command is missing (-c, --cmd)... </info>');
        }

        if (in_array($repeat, ['no', 'n', '-1'])) {
            $repeat = -1;
        } elseif(empty($interval) || in_array($repeat, ['yes', 'y', '*', ''])) {
            $repeat = 0;
        } elseif(!is_numeric($repeat)) {
            return $output->writeln('[-r|--repeat] option value is invalid');
        }

        if ($repeat >= 0 && empty($interval)) {
            return $output->writeln('[-i|--interval] must be specified');
        }

        if(!$sys_cmd) {
            $sys_cmd = null;
        }

        $next_execution = null;
        if(!empty($interval)) {
            $interval_exp = (new TimeExpression($interval));
            $interval = $interval_exp->toSeconds();
            $next_execution = time() + $interval;
        }

        $final = [
            '`name`' => $name,
            '`cmd`' => $command,
            '`sys_cmd`' => $sys_cmd,
            '`repeat`' => $repeat,
            '`interval`' => $interval,
            '`next_execution`' => $next_execution
        ];


        $this->conn->insert('climber_cron', $final);

        $output->writeln('Cron job #'.$this->conn->lastInsertId().' added!');

        if($repeat == 0) {
            $output->writeln('This cron job will be executed indefinitely at interval of '.$interval_exp);
        } elseif($repeat > 0) {
            $output->writeln('This cron job will be executed '.$repeat.' time(s) at interval of '.$interval_exp);
        }

        $output->writeln('Next execution is planned at '.(date('Y-m-d H:i:s', $next_execution)));
    }
}
