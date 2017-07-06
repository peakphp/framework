<?php

namespace Peak\Climber\Commands;

use Peak\Climber\CommandWithDb;
use Peak\Climber\Cron\CronEntity;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CronListCommand extends CommandWithDb
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cron:list')

            // the short description shown while running "php bin/console list"
            ->setDescription('List climber cron jobs')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This will list all climber cron jobs')

            ->setDefinition(
                new InputDefinition([
                    new InputOption('compact', 'c', InputOption::VALUE_NONE, 'Compact list view'),
                    new InputArgument('needle', InputArgument::OPTIONAL),
                ])
            );
    }

    /**
     * Process list
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $compact = $input->getOption('compact');
        $search = $input->getArgument('needle');

        $qb = $this->conn->createQueryBuilder();

        $qb->select('*')
            ->from('climber_cron')
            ->orderBy('id');

        if (!empty($search)) {
            $qb->where('`name` = ?')
                ->orWhere('`id` = ?')
                ->setParameter(0, $search)
                ->setParameter(1, $search);
        }

        $result = $qb->execute();
        $count = $result->rowCount();

        if($count == 0 && !empty($search)) {
            return $output->writeln('No cron job found for '.escapeshellarg($search));
        } elseif($count == 0) {
            return $output->writeln('No cron job yet...');
        }

        $header = $count.' cron job'.(($count>1)?'s':'').':';
        $output->writeln([
            $header,
            str_pad('',strlen($header),'-'),
        ]);

        foreach ($result as $cron) {
            ++$count;
            if ($compact === true) {
                $this->renderCompactCronInfos($cron, $output);
            } else {
                $this->renderCronInfos($cron, $output);
            }
        }
    }

    /**
     * Render normal view of a cron
     *
     * @param $cron
     * @param OutputInterface $output
     */
    protected function renderCronInfos($cron, OutputInterface $output)
    {
        $cron_entity = new CronEntity($cron);
        foreach ($cron as $key => $value) {
            $value = $cron_entity->$key;
            if (empty($value)) {
                $value = '-';
            }
            $output->writeln(' '.str_pad($key, 17,'.').' '.$value );
        }
        $output->writeln('');
    }

    /**
     * Render a compact view of a cron
     *
     * @param $cron
     * @param OutputInterface $output
     */
    protected function renderCompactCronInfos($cron, OutputInterface $output)
    {
        $infos = [];
        $cron_entity = new CronEntity($cron);
        foreach ($cron as $key => $value) {
            $value = $cron_entity->$key;
            if (empty($value)) {
                $value = '-';
            }
            $infos[] = ''.($key).': '.$value;
        }

        $output->writeln(' >>> '.implode(' ', $infos));
    }
}
