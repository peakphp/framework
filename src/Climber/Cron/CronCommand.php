<?php

namespace Peak\Climber\Cron;

use Doctrine\DBAL\Connection;
use Peak\Bedrock\Application\Config;
use Peak\Climber\CommandWithDb;
use Peak\Climber\Cron\Exception\DatabaseNotFoundException;
use Peak\Climber\Cron\Exception\TablesNotFoundException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CronCommand extends CommandWithDb
{
    /**
     * Command prefix
     * @var string
     */
    protected $prefix = 'cron';

    /**
     * Constructor
     *
     * @param Connection $conn
     * @param Config $config
     */
    public function __construct(Connection $conn = null, Config $config)
    {
        if ($config->has('cron.cmd_prefix')) {
            $this->prefix = $config->get('cron.cmd_prefix');
        }
        parent::__construct($conn);
    }

    /**
     * Initializes the command just after the input has been validated.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // run some validation for cron system
        if (!Cron::hasDbConnection($this->conn)) {
            throw new DatabaseNotFoundException('No connection to a database has been found!');
        } elseif ($this->conn->connect() && !Cron::isInstalled($this->conn) && $this->getName() !== 'cron:install') {
            throw new TablesNotFoundException('Cron system is not installed. Please, use command cron:install before using cron commands');
        }
    }
}
