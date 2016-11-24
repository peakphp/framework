<?php
/**
 * PHP class generator
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Class extends Peak_Codegen
{
	/**
	 * Class name
	 * @var string
	 */
	private $_name;
	
	/**
	 * Class extends
	 * @var string
	 */
	private $_extends = null;
	
	/**
	 * Class interface(s)
	 *
	 * @var array
	 */
	private $_interfaces = array();
	
	/**
	 * Class abstract status
	 * @var bool
	 */
	private $_is_abstract = false;
	
	/**
	 * Class final status
	 * @var bool
	 */
	private $_is_final = false;
	
	/**
	 * Class docblock
	 * @var object
	 */
	private $_docblock;
	
	
	/**
	 * Class properties
	 * @var array
	 */
	private $_properties = array();
		
	/**
	 * Class constant(s)
	 * @var array
	 */
	private $_constants = array();
	
	/**
	 * Class method(s)
	 * @var array
	 */
	private $_methods = array();

	
	/**
	 * Set the name of the class
	 *
	 * @param  string $name
	 * @return object $this
	 */
	public function setName($name)
	{
		$this->_name = $name;
		return $this;
	}
	
	/**
	 * Get the class name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Set class extends
	 *
	 * @param  string $classname
	 * @return object $this
	 */
	public function setExtends($classname)
	{
		$this->_extends = $classname;
		return $this;
	}
	
	/**
	 * Get class extends
	 *
	 * @return string
	 */
	public function getExtends()
	{
		return $this->_extends;
	}
	
	/**
	 * Add interface to class
	 *
	 * @param  string|array $interface
	 * @return object       $this
	 */
	public function addInterface($interface)
	{
		if(!is_array($interface)) $this->_interfaces[] = $interface;
		else $this->_interfaces = $interface;
		return $this;
	}
	
	/**
	 * Get interface(s)
	 *
	 * @return array
	 */
	public function getInterfaces()
	{
		return $this->_interfaces;
	}
	
	/**
	 * Set/Get class abstract status 
	 *
	 * @param  bool $abstract
	 * @return bool|object
	 */
	public function isAbstract($abstract = null)
	{
		if(!isset($abstract)) return $this->_is_abstract;
		$this->_is_abstract = $abstract;
		return $this;
	}
	
	/**
	 * Set/Get class final status 
	 *
	 * @param  bool $final
	 * @return bool|object
	 */
	public function isFinal($final = null)
	{
		if(!isset($final)) return $this->_is_final;
		$this->_is_final = $final;
		return $this;
	}
	
	/**
	 * Set docblock object for class
	 *
	 * @param Peak_Codegen_Class_Docblock $doc
	 */
	public function setDocblock(Peak_Codegen_Class_Docblock $doc)
	{
		$this->_docblock = $doc;
	}
	
	/**
	 * Get class docblock
	 *
	 * @return null|object
	 */
	public function getDocblock()
	{
		return $this->_docblock;
	}
	
	/**
	 * Set/Get docblock object for class
	 *
	 * @return Peak_Codegen_Class_DocBlock
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
	 * Add property object
	 *
	 * @param Peak_Codegen_Class_Property $property
	 */
	public function addProperty(Peak_Codegen_Class_Property $property)
	{				
		$this->_properties[$property->getName()] = $property;
	}
	
	/**
	 * Get array of properties objects
	 *
	 * @return array
	 */
	public function getProperties()
	{
		return $this->_properties;
	}
	
	/**
	 * Set/Get property object
	 *
	 * @param  string $name
	 * @return object
	 */
	public function property($name)
	{
		if(isset($this->_properties[$name])) return $this->_properties[$name];
		else {
			$this->_properties[$name] = new Peak_Codegen_Class_Property();
			$this->_properties[$name]->setName($name);
			return $this->_properties[$name];
		}
	}
	
	/**
	 * Add method object
	 *
	 * @param Peak_Codegen_Class_Method $method
	 */
	public function addMethod(Peak_Codegen_Class_Method $method)
	{
		$this->_properties[$method->getName()] = $method;
	}
	
	/**
	 * Get array of methods objects
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return $this->_methods;
	}
	
	/**
	 * Set/Get method object
	 *
	 * @param  string $name
	 * @return object
	 */
	public function method($name)
	{
		if(isset($this->_methods[$name])) return $this->_methods[$name];
		else {
			$this->_methods[$name] = new Peak_Codegen_Class_Method();
			$this->_methods[$name]->setName($name);
			return $this->_methods[$name];
		}
	}
	
	
	/**
	 * Add constant object
	 *
	 * @param Peak_Codegen_Class_Constant $const
	 */
	public function addConstant(Peak_Codegen_Class_Constant $const)
	{
		$this->_constant[$const->getName()] = $const;
	}
	
	/**
	 * Get array of constants objects
	 *
	 * @return array
	 */
	public function getConstant()
	{
		return $this->_constants;
	}
	
	/**
	 * Set/Get constant object
	 *
	 * @param  string $name
	 * @return object
	 */
	public function constant($name)
	{
		if(isset($this->_constants[$name])) return $this->_constants[$name];
		else {
			$this->_constants[$name] = new Peak_Codegen_Class_Constant();
			$this->_constants[$name]->setName($name);
			return $this->_constants[$name];
		}
	}
	
	/**
	 * Generate class content
	 *
	 * @return string
	 */
	public function generate()
	{
		$data = '';

		// class dockblock
		if(isset($this->_docblock)) {			
			$data .= $this->docblock()->generate();			
		}
		
		// class name
		$data .= 'class '.$this->_name;
		
		// abstract class
		if($this->_is_abstract) $data = 'abstract '.$data;
		
		// final class
		if($this->_is_final) $data = 'final '.$data;

		// class extends
		if(!is_null($this->_extends)) $data .= ' extends '.$this->_extends;

		// class interfaces
		if(!empty($this->_interfaces)) $data .= ' implements '.implode(', ',$this->_interfaces);

		$data .= self::LINE_BREAK.'{'.self::LINE_BREAK.self::LINE_BREAK;

		// class constants
		if(!empty($this->_constants)) {
			foreach($this->_constants as $obj) {
				if(!is_null($obj->getDocblock())) {
					$data .= $obj->docblock()->generate($this->getIndent());
				}
				$data .= $this->getIndent().$obj->generate();
			}
		}
		
		// class properties
		if(!empty($this->_properties)) {
			foreach($this->_properties as $obj) {
				if(!is_null($obj->getDocblock())) {
					$data .= $obj->docblock()->generate($this->getIndent());
				}
				$data .= $this->getIndent().$obj->generate();
			}
		}
		
		// class methods
		if(!empty($this->_methods)) {
			foreach($this->_methods as $obj) {
				if(!is_null($obj->getDocblock())) {
					$data .= $obj->docblock()->generate($this->getIndent());
				}
				$data .= $obj->generate($this->getIndent()).self::LINE_BREAK;
			}
		}

		$data .= self::LINE_BREAK.'}';

		return $data;
	}
}