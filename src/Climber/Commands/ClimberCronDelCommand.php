<?php

namespace Peak\Climber\Commands;

use Peak\Climber\Cron\CronCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClimberCronDelCommand extends CronCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName($this->prefix.':del')

            // the short description shown while running "php bin/console list"
            ->setDescription('Delete cron job(s)')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Allow to delete a specific cron job or all cron jobs at once')

            ->setDefinition(
                new InputDefinition([
                    new InputArgument('needle', InputArgument::OPTIONAL),
                    new InputOption('all', '', InputOption::VALUE_NONE, 'delete all cron jobs'),
                    new InputOption('force', 'f', InputOption::VALUE_NONE, 'force delete (skip confirmation)'),
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
        $needle = $input->getArgument('needle');
        $force = $input->getOption('force');
        $all = $input->getOption('all');

        // delete all
        if ($all) {
            $answer = true;
            // ask confirmation if no --force option
            if ($force !== true) {
                $io = new SymfonyStyle($input, $output);
                $answer = $io->confirm('Are you sure about deleting all cron jobs', false);
            }

            $output->writeln('Deleting all cron jobs...');
            $this->conn->executeQuery('DELETE FROM climber_cron');
            return $output->writeln('Done!');
        } elseif (empty($needle)) {
            return $output->writeln('You must specify what you want to delete...');
        }

        $qb = $this->conn->createQueryBuilder();

        $qb->select('*')
            ->from('climber_cron')
            ->where('`name` = ?')
            ->orWhere('`id` = ?')
            ->setParameter(0, $needle)
            ->setParameter(1, $needle);

        $result = $qb->execute();
        $count = $result->rowCount();

        if($count == 0) {
            return $output->writeln('No cron job found for '.escapeshellarg($needle));
        } elseif($count > 1) {

            $output->writeln($count.' results found for '.escapeshellarg($needle).'. Specify the id of you cron job you want to remove instead...');

            $command = $this->getApplication()->find('cron:list');
            return $command->run(new ArrayInput([
                'command' => 'cron:list',
                'needle' => $needle
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
