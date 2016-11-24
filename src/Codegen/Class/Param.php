<?php

/**
 * Class params object for method object
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Class_Param
{
	/**
	 * Param name
	 * @var string
	 */
	private $_name;
	
	/**
	 * Param type
	 * @var string
	 */
	private $_type;
	
	/**
	 * Param default value
	 * @var  misc
	 */
	private $_value;
	
	/**
	 * Set param name
	 *
	 * @param  string $name
	 * @return object
	 */
	public function setName($name)
	{
		$this->_name = $name;
		return $this;
	}
	
	/**
	 * Get param name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Set param value
	 *
	 * @param  misc $value
	 * @return object
	 */
	public function setValue($value)
	{
		$this->_value = $value;
		return $this;
	}
	
	/**
	 * Get param value
	 *
	 * @return misc
	 */
	public function getValue()
	{
		return $this->_value;
	}
	
	/**
	 * Set param type
	 *
	 * @param  string $type
	 * @return object
	 */
	public function setType($type)
	{
		$this->_type = $type;
		return $this;
	}
	
	/**
	 * Get param type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}
	
	/**
	 * Generate param content
	 *
	 * @return string
	 */
	public function generate()
	{
		if(isset($this->_type)) $type = $this->_type.' ';
		else $type = '';
		
		if(isset($this->_value)) {
			if((in_array($this->_value,array('array()','null','true','false'))) || (is_int($this->_value))) {
				$value = ' = '.$this->_value;
			}
			else $value = ' = \''.$this->_value.'\'';
		}
		else $value = '';
		
		return $type . '$'.$this->_name . $value;
	}
	
	
}