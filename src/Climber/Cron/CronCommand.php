<?php

namespace Peak\Climber\Cron;

use Doctrine\DBAL\Connection;
use Peak\Bedrock\Application\Config;
use Peak\Climber\CommandWithDb;
use Peak\Climber\Cron\Exceptions\DatabaseNotFoundException;
use Peak\Climber\Cron\Exceptions\TablesNotFoundException;
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
        if (!CronSystem::hasDbConnection($this->conn)) {
            throw new DatabaseNotFoundException();
        } elseif ($this->conn->connect() && !CronSystem::isInstalled($this->conn) && $this->getName() !== 'cron:install') {
            throw new TablesNotFoundException();
        }
    }
}
