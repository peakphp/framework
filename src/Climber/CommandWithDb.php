<?php

namespace Peak\Climber;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;

abstract class CommandWithDb extends Command
{
    /**
     * @var Connection Cron
     */
    protected $conn;

    /**
     * Constructor.
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        parent::__construct();
        $this->conn = $conn;
    }
}
