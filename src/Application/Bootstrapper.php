<?php

namespace Peak\Application;

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
        \Peak\Application\Bootstrap\ConfigPHP::class,
        \Peak\Application\Bootstrap\ConfigView::class,
        \Peak\Application\Bootstrap\ConfigCustomRoutes::class
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
        foreach($this->default_processes as $process) {
            new $process();
        }
        foreach($this->processes as $process) {
            new $process();
        }

        $this->_boot();
    }

    /**
     * Call all bootstrap methods prefixed by $boot_methods_prefix
     */
    private function _boot()
    {
        $this->_env();

        $c_methods = get_class_methods(get_class($this));
        $l = strlen($this->boot_methods_prefix);
        if(!empty($c_methods)) {
            foreach($c_methods as $m) {            
                if(substr($m, 0, $l) === $this->boot_methods_prefix) $this->$m();
            }
        }
    }

    /**
     * Call environment method if exists
     * e.g. envDev() envProd() envStating() envTesting()
     */
    private function _env()
    {
        if(defined("APPLICATION_ENV")) {
            $env_method = 'env'.APPLICATION_ENV;
            if(method_exists($this, $env_method)) {
                $this->$env_method();
            }
        }
    }
}
