<?php

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application;

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
     * Default app processes before booting
     * @var array
     */
    protected $default_processes = [
        \Peak\Bedrock\Application\Bootstrap\Session::class,
        \Peak\Bedrock\Application\Bootstrap\ConfigPHP::class,
        \Peak\Bedrock\Application\Bootstrap\ConfigView::class,
        \Peak\Bedrock\Application\Bootstrap\ConfigCustomRoutes::class
    ];

    /**
     * Custom app processes before booting
     * @var array
     */
    protected $processes = [];

    /**
     * init app bootstrap
     */
    public function __construct()
    {
        /**
         * Execute processes
         */
        foreach ($this->default_processes as $process) {
            Application::instantiate($process);
        }
        foreach ($this->processes as $process) {
            Application::instantiate($process);
        }

        $this->boot();
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
                    $this->$m();
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
                $this->$env_method();
            }
        }
    }
}
