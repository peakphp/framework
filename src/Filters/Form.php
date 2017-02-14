<?php

namespace Peak\Filters;

use Peak\Filters\Advanced;

/**
 * Form validation/sanitize class
 * This class help to validate/sanitize forms from $_POST or $_GET
 */
abstract class Form extends Advanced  
{

	/**
	 * Form method (post or get)
	 * @var string
	 */
	protected $_method = 'post';

	
	/**
	 * Push $_POST or $_GET data to the class for validation and sanitization
	 *
	 * @param array $data
	 */
	public function __construct()
	{	
		parent::__construct();
		if($this->_method === 'post') $this->_data = $_POST;
		else $this->_data = $_GET;
	}
}
