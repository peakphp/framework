<?php
namespace Peak\Application;

use Peak\Application;
use Peak\Registry;

/**
 * Application Bootstrapper
 *   
 * @author   Francois Lajoie
 */
class Bootstrap
{

    /**
     * init bootstrap
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
        $c_methods = get_class_methods(get_class($this));
        $l = strlen($prefix);
        if(!empty($c_methods)) {
            foreach($c_methods as $m) {            
                if(substr($m, 0, $l) === $prefix) $this->$m();
            }
        }
    }
}