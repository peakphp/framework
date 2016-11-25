<?php
namespace Peak\View;

use Peak\Registry;
use Peak\Config;
use Peak\Core;
use Peak\Helpers as BaseHelpers;

/**
 * Peak View Helpers Object containers
 */
class Helpers extends BaseHelpers
{
    
	/**
	 * Overload helpers properties
	 */
    public function __construct()
    {
    	$this->_prefix    = array('View_Helper_','Peak_View_Helper_');
    	
    	$this->_paths     = array(Core::getPath('views_helpers'),
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
        if(!isset($helper->view) && !($helper instanceof Peak\Config)) {
            $helper->view = Registry::o()->view;
        }
        return $helper;
    }    
}