<?php

/**
 * Abstract elements object
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
abstract class Peak_Codegen_Class_Element
{
	/**
	 * Name of element
	 * @var string
	 */
	protected $_name;
	
	/**
	 * Docblock object
	 * @var object
	 */
	protected $_docblock;
	
	/**
	 * Element visibility
	 * @var string
	 */
	protected $_visibility = 'public';
	
	/**
	 * Static status of element
	 * @var bool
	 */
	protected $_is_static = false;

	/**
	 * Set element name
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
	 * Get element name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Set docblock object
	 *
	 * @param  Peak_Codegen_Class_Docblock $docblock
	 * @return object
	 */
	public function setDocblock(Peak_Codegen_Class_Docblock $docblock)
	{
		$this->_docblock = $docblock;
		return $this;
	}
	
	/**
	 * Get dockblock object
	 *
	 * @return object
	 */
	public function getDocblock()
	{
		return $this->_docblock;
	}
	
	/**
	 * Set/Get docblock object
	 *
	 * @return object
	 */
	public function docblock()
	{
		if(is_object($this->_docblock)) return $this->_docblock;
		else {
			$this->_docblock = new Peak_Codegen_Class_DocBlock();
			return $this->_docblock;
		}
	}
	
	/**
	 * Set element visibility
	 *
	 * @param  string $visibility
	 * @return object
	 */
	public function setVisibility($visibility)
	{
		$this->_visibility = $visibility;
		return $this;
	}
	
	/**
	 * Get object visibility
	 *
	 * @return string
	 */
	public function getVisibility()
	{
		return $this->_visibility;
	}
	
	/**
	 * Set/Get static status
	 *
	 * @param  bool $static
	 * @return bool
	 */
	public function isStatic($static = null)
	{
		if(!isset($static)) return $this->_is_static;
		$this->_is_static = $static;
		return $this;
	}
		
}