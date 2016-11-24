<?php
/**
 * Manipulate array as object with predefined properties and give you control over each property setting and getting.
 * Based on zend framework documentation model and database example.
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
abstract class Peak_Model_DataObject
{
    
    /**
     * Instanciate class and set properties from array
     *
     * @param array $options
     */
    public function __construct(array $properties = null)
    {
        if(is_array($properties)) {
            $this->insertProperties($properties);
        }
    }

    /**
     * Set an existing property value
     *
     * @param string $name
     * @param misc   $value
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if(!method_exists($this, $method)) {
            throw new Peak_Exception('ERR_CUSTOM', __CLASS__.': Invalid property');
        }
        $this->$method($value);
    } 

    /**
     * Get an existing property value
     *
     * @param  string $name
     * @return misc
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if(!method_exists($this, $method)) {
            throw new Peak_Exception('ERR_CUSTOM', __CLASS__.': Invalid property');
        }
        return $this->$method();
    }

    /**
     * Insert an array to object properties
     *
     * @param  array $properties
     * @return object
     */
    public function insertProperties(array $properties)
    {
        $methods = get_class_methods($this);
        foreach ($properties as $key => $value) {
            $method = 'set' . ucfirst($key);
            if(in_array($method, $methods)) $this->$method($value);
        }
        return $this;
    }
    
    /**
     * Check if class has options
     *
     * @param  string $name
     * @return bool
     */
    public function hasProperty($name)
    {
        $method = 'get'.ucfirst($name);
        return (method_exists($this, $method)) ? true : false;
    }
    
    /**
     * Return object properties into associative array
     *
     * @return array
     */
    public function toArray()
    {
        $methods = get_class_methods($this);
        $get_method_regexp = '/^(get)([A-Za-z0-9-_.]+)$/';
        $properties = array();
        foreach ($methods as $method) {
            if(preg_match($get_method_regexp, $method, $matches)) {
                $property = strtolower($matches[2]);
                $properties[$property] = $this->$method();
            }
        }
        return $properties;
    }
}