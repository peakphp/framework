<?php
/**
 * Peak Abstract Helpers Object Containers
 *  
 * @author   Francois Lajoie 
 * @version  $Id$ 
 */
abstract class Peak_Helpers
{

	/**
	 * Class name prefix
	 * @var string|array
	 */
    protected $_prefix = '';

	/**
	 * Helpers file path(s)
	 * @var array
	 */
    protected $_paths = array();

	/**
	 * Helpers objects
	 * @var array
	 */
    protected $_objects = array();

    /**
     * Exception constant
     * @var string
     */
    protected $_exception = 'ERR_DEFAULT';

    /**
     * Exception class
     * @var string
     */
    protected $_exception_class = 'Peak_Exception';

    /**
     * Retreive objects, try to create object if not already setted
     *
     * @param  string $name
     * @return object helper object
     */
	public function __get($name)
	{
		if(isset($this->_objects[$name])) return $this->_objects[$name];
		else
		{		
			$name     = trim(stripslashes(strip_tags($name)));
			$filepath = $this->exists($name, true);

			if($filepath !== false) {
				include_once $filepath;
				
				if(!is_array($this->_prefix)) $this->_prefix = array($this->_prefix);
		
				foreach($this->_prefix as $prefix) {
					if(!class_exists($prefix.$name, false)) continue;
					else {
						$helper_class_name = $prefix.$name;
						break;
					}
				}
				if(!isset($helper_class_name)) throw new $this->_exception_class($this->_exception, $name);
				
				$this->_objects[$name] = new $helper_class_name();
				return $this->_objects[$name];
			}
			else throw new $this->_exception_class($this->_exception, $name);
		}
	}

	/**
	 * Check if $_objects key name exists
	 *
	 * @param  string $object_name
	 * @return bool
	 */
	public function __isset($name)
	{
		return (isset($this->_objects[$name])) ? true : false;
	}

	/**
	 * Unset helper object
	 *
	 * @param string $name
	 */
	public function __unset($name)
	{
		if(array_key_exists($name,$this->_objects)) unset($this->_objects[$name]);
	}

	/**
	 * Check recursively if helper file exists based on $_path
	 *
	 * @param  string $helper_name
	 * @param  string $return_filepath if file found, return filepath instead of true
	 * @return bool
	 */
	public function exists($name, $return_filepath = false)
	{
		$file_found = false;
		foreach($this->_paths as $k => $v) {
			$helper_file = $v.'/'.$name.'.php';
			$helper_file_cs = $v.'/'.ucfirst($name).'.php';
			if(file_exists($helper_file)) {
				return ($return_filepath) ? $helper_file : true;
			}
			elseif(file_exists($helper_file_cs)) {
				return ($return_filepath) ? $helper_file_cs : true;
			}
		}
		return $file_found; 
	}

	/**
	 * Add a path to current paths where helper will look in
	 * This preserve other path
	 * 
	 * @param string $path
	 * 
	 */
	public function addPath($path)
	{
		$this->_paths[] = $path;
	}

	/**
	 * Set paths array where helper will look in
	 * 
	 * @param array $path
	 */
	public function setPaths($path)
	{
		$this->_paths = $path;
	}
	/**
	 * Return the list of path(s) where helper look
	 * 
	 * @return array
	 */
	public function getPaths()
	{
		return $this->_paths;
	}
}