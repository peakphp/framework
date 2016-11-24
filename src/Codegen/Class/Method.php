<?php

/**
 * Class method object
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Class_Method extends Peak_Codegen_Class_Element
{
	/**
	 * Final status
	 * @var bool
	 */
	private $_is_final = false;
	
	/**
	 * Array of params objects
	 * @var array
	 */
	private $_params = array();
	
	/**
	 * Method code
	 * @var string
	 */
	private $_code;
	
	/**
	 * Set param object
	 *
	 * @param  Peak_Codegen_Class_Param $params
	 * @return object
	 */
	public function setParams(Peak_Codegen_Class_Param $params)
	{
		$this->_params = $params;
		return $this;
	}
	
	/**
	 * Get array params of object
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->_params;
	}
	
	/**
	 * Set/Get param object
	 *
	 * @param  string $name
	 * @return bool
	 */
	public function param($name)
	{
		if(isset($this->_params[$name])) return $this->_params[$name];
		else {
			$this->_params[$name] = new Peak_Codegen_Class_Param();
			$this->_params[$name]->setName($name);
			return $this->_params[$name];
		}
	}
	
	/**
	 * Set/Get final status
	 *
	 * @param  bool $final
	 * @return bool|object
	 */
	public function isFinal($final = null)
	{
		if(!isset($final)) return $this->_is_final;
		$this->is_final = $final;
		return $this;
	}
	
	/**
	 * Set method code
	 *
	 * @param string $code
	 */
	public function setCode($code)
	{
		$this->_code = $code;
	}
	
	/**
	 * Get method code
	 *
	 * @return string
	 */
	public function getCode()
	{
		return $this-_code;
	}
	
	/**
	 * Generate method content
	 *
	 * @param  integer $indent
	 * @return string
	 */
	public function generate($indent)
	{
	
		if($this->isStatic()) $static = 'static ';
		else $static = '';
		
		$data = '';
		
		$data .= $indent.$this->_visibility.' '.$static. 'function '.$this->_name.'(';
		
		if(isset($this->_params)) {
			$params = array();
			foreach($this->_params as $obj) {
				$params[] = $obj->generate();
			}
			$params = implode(', ',$params);
			
			$data .= $params;
		}
		
		$data .= ')'.Peak_Codegen::LINE_BREAK.$indent.'{'.Peak_Codegen::LINE_BREAK;
		
		if(isset($this->_code)) {
			$data .= $indent . $indent . $this->_code . Peak_Codegen::LINE_BREAK;
		}
		$data .= $indent.'}'.Peak_Codegen::LINE_BREAK;
		
		return $data;
	}
	
}