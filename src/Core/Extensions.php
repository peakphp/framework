<?php

/**
 * Peak Core Extensions Objects container
 *  
 * @author   Francois Lajoie 
 * @version  $Id$ 
 */
class Peak_Core_Extensions extends Peak_Helpers
{
    
	/**
	 * Overload helpers properties
	 */
    public function __construct()
    {
    	$this->_prefix    = 'Peak_Core_Extension_';
    	
    	$this->_paths     = array(LIBRARY_ABSPATH.'/Peak/Core/Extension');
    			                  
    	$this->_exception = 'ERR_CORE_EXTENSION_NOT_FOUND';
    }
       
}