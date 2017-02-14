<?php

namespace Peak\Filters;

use Peak\Filters\Advanced;

/**
 * Data validation/sanitize class
 * This class help to validate/sanitize array of data
 */
class Data extends Advanced  
{


	/**
	 * Push array to the class for validation and sanitization
	 *
	 * @param array $data
	 */
	public function __construct($data = null)
	{	
		parent::__construct();
		if(isset($data)) $this->setData($data);
	}
	
	/**
	 * Set data to validate/sanitize
	 *
	 * @param array $data
	 */
	public function setData($data)
	{
	    $this->_data = $data;
	}	
}
