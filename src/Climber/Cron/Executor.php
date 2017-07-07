<?php

namespace Peak\Climber\Cron;

use Peak\Climber\Application;
use Peak\Climber\Cron\Exception\DatabaseNotFoundException;
use Peak\Climber\Cron\Exception\TablesNotFoundException;
use Peak\Di\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Executor
{
    /**
     * @var null|object
     */
    protected $conn;

    /**
     * @var Application
     */
    protected $app;

    /**
     * Constructor
     *
     * @param ContainerInterface|null $container
     * @param array $config
     * @throws DatabaseNotFoundException
     * @throws TablesNotFoundException
     */
    public function __construct(ContainerInterface $container = null, array $config = [])
    {

        $this->app = new Application($container, $config);

        new Bootstrap($this->app->conf('crondb'));

        $this->conn = Application::container()->get('CronDbConnection');

        // run some validation for cron system
        if (!Cron::hasDbConnection($this->conn)) {
            throw new DatabaseNotFoundException('No connection to a database has been found!');
        } elseif (!Cron::isInstalled($this->conn)) {
            throw new TablesNotFoundException('Cron system is not installed. Please, use command cron:install before using cron executor.');
        }
    }

    /**
     * Run cron job system
     */
    public function run()
    {
        $qb = $this->conn->createQueryBuilder();

        $qb->select('*')
            ->from('climber_cron')
            ->where('enabled = 1');

        $result = $qb->execute();
        $count = $result->rowCount();

        $time = round(microtime(true));
        //echo date('Y-m-d H:i:s', $time);
        foreach ($result as $cron) {
            if ($cron['repeat'] == -1 && empty($cron['last_execution'])) {
                $this->processCron($cron);
            } elseif ($cron['repeat'] == 0 && ( ($cron['interval'] + $time) <= $cron['last_execution'] ) ) {
                $this->processCron($cron);
            }
        }
    }

    public function processCron($cron)
    {
        $climber_prefix = $this->app->conf('climber_cmd_prefix');

        $update = [
            'last_execution' => time(),
            'error' => 0
        ];

        if ($cron['repeat'] > 1) {
            --$cron['repeat'];
            $update['next_execution'] = time() + $cron['interval'];
        } elseif($cron['repeat'] == 1) {
            $cron['repeat'] = -1;
        }

        if ($cron['repeat'] == -1) {
            $update['enabled'] = 0;
            $update['next_execution'] = null;
        }

        $cmd = $cron['cmd'];
        if (!empty($climber_prefix) && $cron['sys_cmd'] == 0) {
            $cmd = $climber_prefix.' '.$cmd;
        }

        echo 'Running cron job #'.$cron['id'].':'."\n";
        echo '$ '.$cmd."\n";

        $process = new Process($cmd);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $update['error'] = 1;
        }

        $this->conn->update('climber_cron', $update, [
            'id' => $cron['id']
        ]);
//
//        if (!$process->isSuccessful()) {
//            throw new ProcessFailedException($process);
//        }

        echo $process->getOutput();
    }
}
