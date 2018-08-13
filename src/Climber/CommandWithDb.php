<?php

declare(strict_types=1);

namespace Peak\Climber;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;

/**
 * Class CommandWithDb
 * @package Peak\Climber
 */
abstract class CommandWithDb extends Command
{
    /**
     * @var Connection
     */
    protected $conn;

    /**
     * Constructor.
     * @param Connection $conn
     */
    public function __construct(Connection $conn = null)
    {
        parent::__construct();
        $this->conn = $conn;
    }
}
