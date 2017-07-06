<?php

namespace Peak\Climber\Commands;

use Peak\Climber\CommandWithDb;
use Peak\Climber\Cron\CronEntity;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class CronDelCommand extends CommandWithDb
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cron:del')

            // the short description shown while running "php bin/console list"
            ->setDescription('List climber cron jobs')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This will list all climber cron jobs')

            ->setDefinition(
                new InputDefinition([
                    new InputArgument('needle', InputArgument::REQUIRED),
                    new InputOption('all', '', InputOption::VALUE_NONE, 'delete all cron jobs'),
                    new InputOption('force', 'f', InputOption::VALUE_NONE, 'force delete (skip confirmation)'),
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
        $search = $input->getArgument('needle');
        $force = $input->getOption('force');

        $qb = $this->conn->createQueryBuilder();

        $qb->select('*')
            ->from('climber_cron')
            ->where('`name` = ?')
            ->orWhere('`id` = ?')
            ->setParameter(0, $search)
            ->setParameter(1, $search);

        $result = $qb->execute();
        $count = $result->rowCount();

        if($count == 0) {
            return $output->writeln('No cron job found for '.escapeshellarg($search));
        } elseif($count > 1) {

            $output->writeln($count.' results found for '.escapeshellarg($search).'. Specify the id of you cron job you want to remove instead...');

            $command = $this->getApplication()->find('cron:list');
            return $command->run(new ArrayInput([
                'command' => 'cron:list',
                'needle' => $search
            ]), $output);
        }

        $data = $result->fetch();
        if(!empty($data['name'])) {
            $data['name'] = '('.$data['name'].')';
        }

        $answer = true;

        // ask confirmation if no --force option
        if ($force !== true) {
            $io = new SymfonyStyle($input, $output);
            $answer = $io->confirm('Are you sure about deleting cron #'.$data['id'].$data['name'],false);
        }

        // delete the cron
        if ($answer === true) {
            $this->conn->delete('climber_cron', ['id' => $data['id']]);
            $output->writeln('cron #'.$data['id'].$data['name'].' removed');
        }
    }
}
