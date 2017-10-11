<?php

namespace Peak\Bedrock\Application;

use Psr\Container\ContainerInterface;

/**
 * Application Bootstrapper
 */
class Bootstrapper
{
    /**
     * Prefix of methods to call on boot
     * @var string
     */
    protected $boot_methods_prefix = 'init';

    /**
     * App processes before booting
     * @var array
     */
    protected $processes = [];

    /**
     * Application container
     * @var array
     */
    protected $container = [];

    /**
     * init app bootstrap
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->runProcesses();
        $this->boot();
    }

    /**
     * Run process
     */
    protected function runProcesses()
    {
        foreach ($this->processes as $process) {
            $this->container->create($process);
        }
    }

    /**
     * Call all bootstrap methods prefixed by $boot_methods_prefix
     */
    private function boot()
    {
        $this->env();

        $c_methods = get_class_methods(get_class($this));
        $l = strlen($this->boot_methods_prefix);
        if (!empty($c_methods)) {
            foreach ($c_methods as $m) {
                if (substr($m, 0, $l) === $this->boot_methods_prefix) {
                    $this->container->call([$this, $m]);
                }
            }
        }
    }

    /**
     * Call environment method if exists
     * e.g. envDev() envProd() envStating() envTesting()
     */
    private function env()
    {
        if (defined('APPLICATION_ENV')) {
            $env_method = 'env'.APPLICATION_ENV;
            if (method_exists($this, $env_method)) {
                $this->container->call([$this, $env_method]);
            }
        }
    }
}
