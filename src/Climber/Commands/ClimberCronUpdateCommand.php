<?php

declare(strict_types=1);

namespace Peak\Climber\Commands;

use Peak\Climber\Cron\CronCommand;
use Peak\Common\TimeExpression;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClimberCronUpdateCommand extends CronCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName($this->prefix.':update')

            // the short description shown while running "php bin/console list"
            ->setDescription('Update cron job')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This will update a cron job')

            ->setDefinition(
                new InputDefinition([
                    new InputOption('name', null, InputOption::VALUE_REQUIRED, 'Cron internal name'),
                    new InputOption('sys', '', InputOption::VALUE_NONE, 'Cron command is a system command'),
                    new InputOption('climber', '', InputOption::VALUE_NONE, 'Cron command is a climber command'),
                    new InputOption('repeat', 'r', InputOption::VALUE_REQUIRED, 'Indicate if command should be repeatable', '-1'),
                    new InputOption('interval', 'i', InputOption::VALUE_REQUIRED, 'Indicate the interval in second between repetition if apply.'),
                    new InputOption('cmd', 'c', InputOption::VALUE_REQUIRED, 'The command to execute.'),
                    new InputOption('enable', null, InputOption::VALUE_NONE, 'Enable the command'),
                    new InputOption('disable', null, InputOption::VALUE_NONE, 'Disable the command'),
                    new InputArgument('needle', InputArgument::REQUIRED),
                ])
            );
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     * @throws \Exception
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getOption('cmd');
        $repeat = $input->getOption('repeat');
        $interval = $input->getOption('interval');
        $name = $input->getOption('name');
        $enable = $input->getOption('enable');
        $disable = $input->getOption('disable');
        $sys_cmd = $input->getOption('sys');
        $climber_cmd = $input->getOption('climber');
        $needle = $input->getArgument('needle');


        if (empty($needle)) {
            return $output->writeln('<error>Cron job id or name is needed!</error>');
        }

        $qb = $this->conn->createQueryBuilder();

        $qb->select('*')
            ->from('climber_cron')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('`name`', ':search'),
                    $qb->expr()->eq('`id`', ':search')
                )
            )
            ->setParameter('search', $needle);

        $cron = $qb->execute()->fetchAll();
        $count = count($cron);

        if (empty($cron)) {
            return $output->writeln('No cron job found for '.escapeshellarg($needle));
        } elseif ($count > 1) {
            $output->writeln($count.' results found for '.escapeshellarg($needle).'. Specify the id of you cron job you want to update instead...');

            $command = $this->getApplication()->find('cron:list');
            return $command->run(new ArrayInput([
                'command' => 'cron:list',
                'needle' => $needle
            ]), $output);
        }

        $update = [];
        $cron = $cron[0];

        if ($sys_cmd) {
            $update['sys_cmd'] = 1;
        }

        if ($climber_cmd) {
            $update['sys_cmd'] = 0;
        }

        if ($enable) {
            $update['enabled'] = 1;
        }

        if ($disable) {
            $update['enabled'] = 0;
        }

        if ($name) {
            $update['name'] = $name;
        }

        if ($interval) {
            $interval_exp = (new TimeExpression($interval));
            $interval = $interval_exp->toSeconds();
            if (empty($cron['last_execution'])) {
                $update['next_execution'] = time() + $interval;
            } else {
                $update['next_execution'] = $cron['last_execution'] + $interval;
            }
        }

        $this->conn->update('climber_cron', $update, [
            'id' => $cron['id']
        ]);

        $output->writeln('Cron job #'.$cron['id'].' updated!');
    }
}
