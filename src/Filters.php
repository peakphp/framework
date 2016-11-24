<?php
/**
 * Filter base wrapper
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
abstract class Peak_Filters 
{
	/**
	 * Data on which we work 
	 * @var array
	 */
	protected $_data = array();

	/**
	 * Sanitize filters
	 * @var array
	 */
	protected $_sanitize;

	/**
	 * Global sanitize filters for all data
	 * @var array
	 */
	protected $_global_sanitize;

	/**
	 * Validate filters
	 * @var array
	 */
	protected $_validate;

	/**
	 * Errors found when validating
	 * @var array
	 */
	protected $_errors = array();

	/**
	 * 
	 */
	public function __construct()
	{		
		// call setUp method if exists
		if(method_exists($this, 'setUp')) $this->setUp();

		// call those methods if exists to gather validate and sanitize filters from child class
		if(method_exists($this,'setSanitization')) $this->_sanitize = $this->setSanitization();
		if(method_exists($this,'setValidation')) $this->_validate = $this->setValidation();
		if(method_exists($this,'setGlobalSanitization')) $this->_global_sanitize = $this->setGlobalSanitization();
	}

	/**
	 * Get data
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->_data;
	}

	/**
	 * Get sanitize filters var
	 *
	 * @return array
	 */
	public function getSanitizeFilters()
	{
		return $this->_sanitize;
	}

	/**
	 * Get global sanitize filter
	 *
	 * @return array
	 */
	public function getGlobalSanitizeFilter()
	{
	    return $this->_global_sanitize;
	}

	/**
	 * Get validate filters var
	 *
	 * @return array
	 */
	public function getValidateFilters()
	{
		return $this->_validate;
	}

	/**
	 * Get errors
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * Sanitize all data with $_global_sanitize
	 */
	public function globalSanitize()
	{
	    $filter = isset($this->_global_sanitize['filter']) ? $this->_global_sanitize['filter'] : null;
	    $flags  = isset($this->_global_sanitize['flags'])  ? $this->_global_sanitize['flags']  : null;
	    
	    // if no global sanitize filter is set, we use a default string sanitize
        if(is_null($filter)) {
            $filter = FILTER_SANITIZE_STRING;
            $flags  = array(FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_STRIP_LOW);
        }
        $this->_data = $this->_globalSanitizeRecursive($this->_data, $filter, $flags);
	    
	    return $this->_data;
	}

	/**
	 * Make globalSanitize() recursive
	 *
	 * @param  array   $array
	 * @param  integer $filter
	 * @param  integer $flags
	 * @return array
	 */
	private function _globalSanitizeRecursive($array, $filter, $flags)
	{
	    foreach($array as $k => $v) {
	        if(!is_array($v)) $array[$k] = filter_var($v, $filter, $flags);
	        else {
	            $array[$k] = $this->_globalSanitizeRecursive($v, $filter, $flags);
	        }
	    }
	    return $array;
	}
}