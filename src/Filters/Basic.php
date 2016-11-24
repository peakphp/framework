<?php
/**
 * Filter Basic extension wrapper for sanitization and/or validation of array
 * 
 * @uses    PHP Filter extensions for sanitazation and validation
 * @author  Francois Lajoie
 * @version $Id$
 * 
 * @dependencies Peak_Filters
 */
abstract class Peak_Filters_Basic extends Peak_Filters
{
	/**
	 * Errors msg gather from $_validate
	 * @var array
	 */
	protected $_errors_msg = array();

	
	/**
	 * Push array to the class for validation and sanitization
	 *
	 * @param array $data
	 */
	public function __construct($data)
	{
		if(!is_array($data)) trigger_error('MUST BE AN ARRAY');
		else $this->_data = $data;

		parent::__construct();
	}
	
	/**
	 * Sanitize $_data using $_sanitize filters
	 * 
	 * @return array 
	 */
	public function sanitize()
	{	
		$filters = $this->_sanitize;
		
		$this->_data = filter_var_array($this->_data, $filters);
		
		return $this->_data;	
	}
	
	/**
	 * Validate $_data using $_validate filters
	 *
	 * @return bool
	 */
	public function validate()
	{
		$filters = $this->_array2def($this->_validate);
		
		$data = filter_var_array($this->_data, $filters);
		
		foreach($data as $k => $v)
		{			
	
			if((isset($this->_validate[$k]['flags']) && $this->_validate[$k]['flags'] == 134217728)) {
				if(is_null($v) || ($v === false)) {
					$this->_errors[$k] = 'fail';
				}
			}
			elseif($v === false) {
				$this->_errors[$k] = 'fail';
			}
			
			if(isset($this->_errors[$k]) && (isset($this->_errors_msg[$k]))) {
				$this->_errors[$k] = $this->_errors_msg[$k];
			}
		}
		
		if(!empty($this->_errors)) return false;
		else return true;
	}
	
		
	/**
	 * Set error message for data keyname
	 * Usefull for FILTER_CALLBACK method
	 *
	 * @param string $name
	 * @param string $message
	 */
	protected function _setError($name, $message)
	{
		$this->_errors_msg[$name] = $message;
	}
	
	/**
	 * Shorcut of FILTER_VALIDATE_REGEXP
	 * Usefull for FILTER_CALLBACK methods
	 *
	 * @param  string $value
	 * @param  string $regexp
	 * @return string|false
	 */
	protected function _regexp($value, $regexp)
	{
		return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $regexp)));
	}
	

	
	/**
	 * Transform an array to valid filters array
	 *
	 * @param  array  $array 
	 * @param  string $type 'sanitize' or 'validate'
	 * @return array
	 */
	private function _array2def($array)
	{
	
		if(is_array($array)) {
								
			foreach($array as $k => $v)
			{
				if(is_array($v)) {
										
					//errors (push errors string to $this->_errors_msg because they are not a part of filters definition
					if(isset($v['error'])) {
						$this->_errors_msg[$k] = $v['error'];
						unset($array[$k]['error']);
					}
				}
			}
			
			return $array;
		}
		return $this->_data;
	}
	
}