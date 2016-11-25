<?php
namespace Peak\Controller\Helper;

use Peak\Filters\Advanced;

/**
 * Validate Filters extension wrapper
 */
class Validate extends Advanced
{

    public function __call($method, $args = null)
    {
        if($this->_filterExists($method)) {
            return call_user_func_array(array($this, $this->_filter2method($method)), $args);
        }
    }

}