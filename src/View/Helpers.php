<?php

/**
 * Peak View Helpers Object containers
 *  
 * @author   Francois Lajoie 
 * @version  $Id$
 */
class Peak_View_Helpers extends Peak_Helpers
{
    
	/**
	 * Overload helpers properties
	 */
    public function __construct()
    {
    	$this->_prefix    = array('View_Helper_','Peak_View_Helper_');
    	
    	$this->_paths     = array(Peak_Core::getPath('views_helpers'),
    			                  LIBRARY_ABSPATH.'/Peak/View/Helper');
    			                  
    	$this->_exception = 'ERR_VIEW_HELPER_NOT_FOUND';
    	
    	$this->_exception_class = 'Peak_View_Exception';
    }
    
    /**
     * Add view object to helper objects
     *
     * @param  string $name
     * @return object
     */
    public function __get($name)
    {
        $helper = parent::__get($name);
        if(!isset($helper->view) && !($helper instanceof Peak_Config)) {
            $helper->view = Peak_Registry::o()->view;
        }
        return $helper;
    }    
}