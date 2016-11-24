<?php

/**
 * Class constant object
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Class_Constant extends Peak_Codegen_Class_Element 
{
	/**
	 * Constant value
	 * @var string
	 */
	private $_value;
	
	/**
	 * Set constant value
	 *
	 * @param  string $value
	 * @return object
	 */
	public function setValue($value)
	{
		$this->_value = $value;
		return $this;
	}
	
	/**
	 * Get constant value
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->_value;
	}
	
	/**
	 * Generate constant line
	 *
	 * @return string
	 */
	public function generate()
	{
		if(is_int($this->_value)) $value = $this->_value;
		else $value = '\''.$this->_value.'\'';
		return 'const '.strtoupper($this->_name).' = '.$value.';'.Peak_Codegen::LINE_BREAK.''.Peak_Codegen::LINE_BREAK;
	}
}
