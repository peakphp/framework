<?php

/**
 * Class property object
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Class_Property extends Peak_Codegen_Class_Element 
{
	/**
	 * Property value
	 *
	 * @var string
	 */
	private $_value;
	
	/**
	 * Set property value
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
	 * Get property value
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->_value;
	}
	
	/**
	 * Generate property content
	 *
	 * @return string
	 */
	public function generate()
	{	
		if(isset($this->_value)) {
			if((in_array($this->_value,array('array()','null','true','false'))) || (is_int($this->_value))) {
				$value = ' = '.$this->_value;
			}
			else $value = ' = \''.$this->_value.'\'';
		}
		else $value = '';
		
		if($this->isStatic()) $static = 'static ';
		else $static = '';
		
		return $this->getVisibility().' '.$static.'$'.$this->_name . $value .';' .Peak_Codegen::LINE_BREAK . Peak_Codegen::LINE_BREAK;
	}
}