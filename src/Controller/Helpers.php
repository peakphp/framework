<?php
/**
 * Peak Controller Helpers Objects container
 *  
 * @author   Francois Lajoie 
 * @version  $Id$ 
 */
class Peak_Controller_Helpers extends Peak_Helpers
{
    
	/**
	 * Overload helpers properties
	 */
    public function __construct()
    {
    	$this->_prefix    = array('Controller_Helper_', 'Peak_Controller_Helper_');
    	
    	$this->_paths     = array(Peak_Core::getPath('controllers_helpers'),
    			                  LIBRARY_ABSPATH.'/Peak/Controller/Helper');
    			                  
    	$this->_exception = 'ERR_CTRL_HELPER_NOT_FOUND';
    	
    	$this->_exception_class = 'Peak_Controller_Exception';
    }

}