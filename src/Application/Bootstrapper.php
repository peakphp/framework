<?php
namespace Peak\Application;

/**
 * Application Bootstrapper
 */
class Bootstrapper
{

    /**
     * init app bootstrap
     */
    public function __construct()
    {
        new Bootstrap\View();
        $this->_boot();
    }

    /**
     * Call all bootstrap methods prefixed by "init"
     *
     * @param string $prefix
     */
    private function _boot($prefix = 'init')
    {
        $this->_env();

        $c_methods = get_class_methods(get_class($this));
        $l = strlen($prefix);
        if(!empty($c_methods)) {
            foreach($c_methods as $m) {            
                if(substr($m, 0, $l) === $prefix) $this->$m();
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