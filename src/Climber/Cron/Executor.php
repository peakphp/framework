<?php

namespace Peak\Climber\Cron;

use Peak\Climber\Application;
use Peak\Climber\Cron\Exception\DatabaseNotFoundException;
use Peak\Climber\Cron\Exception\TablesNotFoundException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use \Exception;

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
     * Default prefix for executing climber commands
     * @var string
     */
    protected $default_prefix = 'php climber';

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

        new BootstrapDatabase($this->app->conf('cron.db'));

        $this->conn = Application::container()->get('CronDbConnection');

        // run some validation for cron system
        if (!Cron::hasDbConnection($this->conn)) {
            throw new DatabaseNotFoundException();
        } elseif (!Cron::isInstalled($this->conn)) {
            throw new TablesNotFoundException();
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
            ->where('enabled = 1')
            ->where('in_action = 0');

        $result = $qb->execute()->fetchAll();
        $count = count($result);

        $time = time();

        //echo date('Y-m-d H:i:s', $time);
        foreach ($result as $cron) {
            $process = false;
            if ($cron['repeat'] == -1 && empty($cron['last_execution'])) {
                $process = true;
            } elseif ($cron['repeat'] == 0) {
                if (($cron['interval'] + $cron['last_execution']) <= $time) {
                    $process = true;
                } elseif (empty($cron['last_execution'])) {
                    $process = true;
                }
            } elseif ($cron['repeat'] > 0 && (($cron['interval'] + $cron['last_execution']) <= $time)) {
                $process = true;
            }

            if ($process) {
                $this->processCron($cron);
            }
        }
    }

    /**
     * Process cron job
     * @param $cron
     */
    public function processCron($cron)
    {
        $climber_prefix = $this->default_prefix;

        if ($this->app->conf()->has('cron.processor_prefix')) {
            $climber_prefix = $this->app->conf('cron.processor_prefix');
        }

        $update = [
            'last_execution' => time(),
            'status' => 1,
            'error' => '',
            'next_execution' => time() + $cron['interval'],
            'in_action' => 0
        ];

        if ($cron['repeat'] > 1) {
            --$cron['repeat'];
        } elseif($cron['repeat'] == 1) {
            $cron['repeat'] = -1;
        }

        $update['repeat'] = $cron['repeat'];

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

        // lock job
        $this->conn->update('climber_cron',
            ['in_action' => 1],
            ['id' => $cron['id']]
        );

        // run the job
        try {
            $process = new Process($cmd);
            $process->run();
            if (!$process->isSuccessful()) {
                $update['status'] = 0;
                throw new ProcessFailedException($process);
            }
        } catch(Exception $e) {
            $update['error'] = $e->getMessage();
            echo 'Cron #'.$cron['id'].' failed!';
        }

        $this->conn->update('climber_cron', $update, [
            'id' => $cron['id']
        ]);

        echo $process->getOutput();
    }
}
