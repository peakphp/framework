<?php

namespace Peak\Climber\Commands;

use Peak\Climber\Cron\CronEntity;
use Peak\Climber\Cron\CronCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CronListCommand extends CronCommand
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
                    new InputOption('status', 's', InputOption::VALUE_OPTIONAL, 'Filter by status'),
                    new InputOption('list', 'l', InputOption::VALUE_NONE, 'List view'),
                    new InputOption('compact', 'c', InputOption::VALUE_NONE, 'Compact list view'),
                    new InputOption('disabled', null, InputOption::VALUE_NONE, 'Show only disabled'),
                    new InputOption('enabled', null, InputOption::VALUE_NONE, 'Show only enabled'),
                    new InputArgument('needle', InputArgument::OPTIONAL),
                ])
            );
    }

    /**
     * Process list
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $list = $input->getOption('list');
        $compact = $input->getOption('compact');
        $disabled = $input->getOption('disabled');
        $status = $input->getOption('status');
        $enabled = $input->getOption('enabled');
        $needle = $input->getArgument('needle');

        $qb = $this->conn->createQueryBuilder();

        $qb->select('*')
            ->from('climber_cron')
            ->orderBy('id');

        if (!empty($needle)) {
            $qb->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('`name`', ':search'),
                    $qb->expr()->eq('`id`', ':search')
                )
            )
            ->setParameter('search', $needle);
        }

        if ($disabled === true) {
            $qb->where('enabled = 0');
        }

        if ($enabled === true) {
            $qb->where('enabled = 1');
        }


        if ($status) {
            if ($status === true) {
                $qb->where('`status` is NULL');
            } else {
                $qb->where('`status` = :status')
                    ->setParameter('status', $status);
            }


        }

        echo $qb->getSQL();

        $result = $qb->execute()->fetchAll();
        $count = count($result);

        if($count == 0 && !empty($needle)) {
            return $output->writeln('No cron job found for '.escapeshellarg($needle));
        } elseif($count == 0) {
            return $output->writeln('No cron job found...');
        }

        $header = $count.' cron job'.(($count>1)?'s':'').':';
        $output->writeln($header);

        foreach ($result as $cron) {
            if ($compact === true) {
                $this->renderCompactCronInfos($cron, $output);
            } elseif ($list === true) {
                $this->renderCronInfos($cron, $output);
            } else {
                return $this->renderTable($result, $output);
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

        $output->writeln(' >>> '.implode(' | ', $infos));
    }

    /**
     * Render a table list
     *
     * @param $data
     * @param OutputInterface $output
     */
    protected function renderTable($data, OutputInterface $output)
    {
        $header = [];
        $rows = [];
        foreach ($data as $index => $row) {
            $cron_entity = new CronEntity($row);
            foreach($row as $key => $data) {
                if (!in_array($key, $header)) {
                    $header[] = $key;
                }
                $row[$key] = $cron_entity->$key;

                if ($key === 'error') {
                    $content = explode("\n", $row[$key]);
                    if (isset($content[0])) {
                        $row[$key] = $content[0];
                        if (isset($content[1])) {
                            $row[$key] .= '...';
                        }
                    }
                }
            }
            $rows[] = array_values($row);
        }

        $table = new Table($output);
        $table
            ->setHeaders($header)
            ->setRows($rows);
        $table->render();
    }
}
