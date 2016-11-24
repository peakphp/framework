<?php
/**
 * Basic active record using Zend_Db as data source provider
 *
 * @uses     Peak_Model_Zendatable
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_Model_ActiveRecord
{
    /**
     * Entity data
     * @var array
     */
    protected $_data = array();

    /**
     * Table model
     * @var object
     */
    protected $_tbl;

    /**
     * The model class name
     * @var string
     */
    protected $_model_classname;

    /**
     * Can we overwrite $_data ? Will also "lock" this object.
     * @var boolean
     */
    protected $_readonly = false;

    /**
     * This flag tell if the record is a valid existing record of the model. 
     * @var boolean
     */
    private $_exists = false;

    /**
     * Constructor
     * 
     * @param null|array|integer $data
     * @param null|string|object $model if specified, $model is used instead of $this->_model_classname
     * @param bool               $realonly false by default
     */
    public function __construct($data = null, $model = null, $readonly = false)
    { 
        // load model object
        if(!is_null($model) && !empty($model)) {
            if(is_object($model)) $this->_tbl = $model;
            else $classname = $model;
        }
        elseif(!empty($this->_model_classname)) {
            $classname = $this->_model_classname;
        }

        // try to load model
        if(isset($classname)) {
            if(class_exists($classname)) {
                $this->_tbl = new $classname();
            }
            else {
                throw new Exception(__CLASS__.' : Model class '.$classname.' not found');
            }
        }

        //model check
        if(is_object($this->_tbl) && !($this->_tbl instanceof Peak_Model_Zendatable)) {
            throw new Exception(__CLASS__.' : Model class '.get_class($this->_tbl).' is not an instance of Peak_Model_Zendatable');
        }

        // load data 
        if(is_array($data)) {
            $safe = true;
            if(!is_object($this->_tbl)) $safe = false; //raw data, no check
            $this->setData($data, $safe);
        }
        elseif(is_string($data) || is_numeric($data)) {
            $this->_data = $this->_tbl->findId($data);
        }
        elseif(!isset($data)) {
            throw new Exception(__CLASS__.' : Invalid var format for constructor $data. Only array or integer is supported');
        }

        // test the record existence (need a model)
        if(is_object($this->_tbl)) {
            $pk = $this->_tbl->getPrimaryKey();
            if(array_key_exists($pk, $this->_data)) {

                $test = $this->_tbl->findId($this->_data[$pk]);
                $this->_exists = (empty($test)) ? false : true;
            }
        }

        // turn on readonly
        if($readonly === true) $this->readOnly();
    }

    /**
     * Retreive a column from $_data
     *
     * @param  string $key
     * @return string|null
     */
    public function __get($key)
    {
        if(array_key_exists($key, $this->_data)) return $this->_data[$key];
        else return null;
    }

    /**
     * Set/Overwrite a key in $_data IF $_readonly is true
     * 
     * @param string $key  
     * @param string $name 
     */
    public function __set($key, $name)
    {
        if($this->_readonly === false) $this->_data[$key] = $name;
    }

    /**
     * Isset data keyname
     * 
     * @param  string  $key
     * @return boolean     
     */
    public function __isset($key)
    {
        return array_key_exists($key, $this->_data);
    }

    /**
     * Set data to this entities
     * 
     * @param array   $array 
     * @param boolean $safe  if true, array must be compliant to a record normally used in database model (based on the primary key)
     */
    public function setData($array, $safe = true)
    {
        if($this->_readonly === true) return;
        
        if(!$safe) {
            $this->_data = $array;
            return;
        }

        if(empty($array) || !array_key_exists($this->_tbl->getPrimaryKey(), $array)) {
            throw new Exception('Models Entities: data submited to setData() is not a valid record of '.__CLASS__);    
        }
        else $this->_data = $array;
    }

    /**
     * Return the _data array
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Check if the entities is valid(exists) and $_data is populated
     * 
     * @return boolean
     */
    public function exists()
    {
        return $this->_exists;
    }

    /**
     * Save data using the $_tbl model object
     */
    public function save()
    {
        //readonly skip this
        if($this->_readonly === true) return false;
        
        $pk = $this->_tbl->getPrimaryKey();

        // force save
        if(!$this->exists()) {

            $new_id           = $this->_tbl->save($this->_data, true);
            $this->_data[$pk] = $new_id;
            $this->_exists    = true;
        }
        else {
            $this->_tbl->save($this->_data);
        }
    }

    /**
     * Delete the record
     * @return bool
     */
    public function delete()
    {
        // don't exists or readonly, skip this
        if(!$this->exists() || $this->_readonly === true) return false;

        $pk     = $this->_tbl->getPrimaryKey();
        $where  = $this->_tbl->quoteInto($pk.' = ?', $this->_data[$pk]);
        $result = $this->_tbl->delete($where);

        if($result === true) {
            $this->_exists = false;
        }

        return $result;
    }

    /**
     * Mark as readonly. save(),delete() won't work and $_tbl will be null
     *
     * @final
     */
    final public function readOnly()
    {
        $this->_readonly = true;
        $this->_tbl = null;
    }
}