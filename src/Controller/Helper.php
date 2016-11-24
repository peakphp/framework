<?php

/**
 * Abstract base class for controllers helpers
 * 
 * @author   Francois Lajoie 
 * @version  $Id$ 
 */
abstract class Peak_Controller_Helper
{
    /**
     * Unkown method in Controller Helper will try to call current controller __call() method.
     * So you can load another controllers helpers inside helper
     *
     * @param  string $method
     * @param  array $args
     * @return misc
     */
    public function  __call($method, $args = null)
    {
        return call_user_func_array(array($this->controller(), $method), $args);
    }
    
    /**
     * Access to controller properties and methods
     *
     * @return object
     */
    public function controller()
    {
        return Peak_Registry::o()->app->front->controller;
    }
}