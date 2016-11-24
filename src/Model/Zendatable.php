<?php
/**
 * Peak_Model_Zendatable
 * Base for db tables using Zend_Db
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
abstract class Peak_Model_Zendatable extends Zend_Db_Table_Abstract
{
    /**
     * Count from the latest fetch
     * @var integer
     */
    public $last_count = null;
    
    /**
     * Adapter db link
     * @var object
     */
    protected $_db;
    
    /**
     * Pagination object
     * @var object
     */
    protected $_paging;

    /**
     * Set db default adpater
     */
    public function __construct()
    {
        $this->_db = $this->getDefaultAdapter();
    }

    /**
     * Unknow method, we try to call the method in $_db
     *
     * @param string $method
     * @param array  $args
     */
    public function  __call($method, $args = null)
    {
        if(method_exists($this->_db, $method)) {
            return call_user_func_array(array($this->_db, $method), $args);        
        }
        else {
            throw new Peak_Exception('ERR_CUSTOM', __CLASS__.': unknow method '.strip_tags($method).'()');
        }
    }

    /**
     * Work like quoteIdentifier() but accept multiple identifiers as string seperated by , or an array
     * 
     * @param  string|array $val
     * @return string
     */
    public function quoteIdentifiers($val)
    {   
        $final = '';

        if(!is_array($val)) {
            $val = explode(',', $val);
        }

        if(!empty($val)) {
            foreach($val as $i => $v) {
                $val[$i] = $this->_db->quoteIdentifier(trim($v));
            }
        }
        $final = implode(',', $val);

        return $final;
    }

    /**
     * Get table columns
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->_getCols();
    }

    /**
     * Delete primary key(s)
     *
     * @param  integer|array $ids
     * @return integer
     */
    public function deleteId($primary_keys)
    {
        $id_key = $this->getPrimaryKey();
            
        if(is_array($primary_keys)) $where = $this->_db->quoteInto($id_key.' IN(?)', $primary_keys);
        else $where = $this->_db->quoteInto($id_key.' = ?', $primary_keys);

        return $this->delete($where);
    }

    /**
     * Check if a specific key exists in table
     *
     * @param  misc       $val
     * @param  string     $key
     * @param  bool       $return_row if true, the method will return the row found if any instead of returning true
     * @return bool|array
     */
    public function exists($val, $key = null, $return_row = false)
    {
        if(!isset($key)) $key = $this->getPrimaryKey();

        $field = (!$return_row) ? $key : '*';

        $select = $this->select()->from($this->getSchemaName(), $field)
                                 ->where($this->_db->quoteInto('`'.$key.'` = ?',$val));                     
        
        $result = $this->fetchRow($select);

        if(!$return_row) return (is_null($result)) ? false : true;
        else return (is_null($result)) ? false : $result->toArray();
    }

    /**
     * Find something by its primary key. If $_object_mapper exists
     * will return data(s) row(s) in form of object mapper
     *
     * @param  array|integer $ids
     * @return array
     */
    public function findId($ids)
    {
        $result = parent::find($ids);
        $this->last_count = $result->count();
        $data = $result->toArray();
        
        if(isset($this->_object_mapper)) {
            $object_mapper = $this->_object_mapper;
            foreach($data as $i => $row) {
                $data[$i] = new $object_mapper($row);
            }
        }
        
        if(count($data) == 1) $data = $data[0];
        
        return $data;
    }

    /**
     * Get a single row (fetch)
     * 
     * @param  @see _get()
     * @return array
     */
    public function get($p = array())
    {
        return $this->_get($p)->fetch();
    }

    /**
     * Get all rows (fetchAll)
     * 
     * @param  @see _get()
     * @return array
     */
    public function getAll($p = array())
    {
        return $this->_get($p)->fetchAll();
    }

    /**
     * Internal _get() manly used by get() and getAll(). 
     * You need to apply one of fetch methods on result to get array
     * 
     * @param  array $p Params array. Possible options are: "fields", "where", "bind", "group", "order", "by", "limit".
     *                  Options "fields" and "bind" values are arrays, others are string
     * 
     * @return Zend_Db_Statement_Pdo
     */
    public function _get($p = array())
    {
        //selected fields
        if(!isset($p['fields']) || empty($p['fields']) || !is_array($p['fields']) || $p['fields'] === '*') {
            $fields = '*';
        }
        else {

            foreach($p['fields'] as $i => $f) {
                if(is_numeric($i)) {
                    $p['fields'][$i] = $this->quoteIdentifier($f);
                }
                else {
                    // detect special field like COUNT(myfield) as total
                    preg_match('#(?<func>[a-zA-Z]+)\((?<field>[a-zA-Z0-9]+)\)#', $i, $match);
  
                    if(is_array($match) && !empty($match)) {
                        $p['fields'][] = $match['func'].'('.$this->quoteIdentifier($match['field']).') AS '.$this->quoteIdentifier($f);
                    }
                    else {
                        $p['fields'][] = $this->quoteIdentifier($i).' AS '.$this->quoteIdentifier($f);
                    }
                    unset($p['fields'][$i]);
                }
            }
            $fields = implode(',', $p['fields']);
        }

        $req = 'SELECT '.$fields.' FROM '.$this->getSchemaName();

        // where
        if(isset($p['where']) && !empty($p['where'])) {
            $req .= ' WHERE '.$p['where'];
        }

        // where bind
        $bind = (!isset($p['bind'])) ? null : $p['bind'];

        // group by
        if(isset($p['group']) && !empty($p['group'])) {
            $req .= ' GROUP BY '.$this->quoteIdentifiers($p['group']);
        }

        // order by
        if(isset($p['order']) && !empty($p['order'])) {
            $req .= ' ORDER BY '.$this->quoteIdentifiers($p['order']);
            if(isset($p['by']) && strtolower($p['by']) === 'desc') $req .= ' DESC';
            else $req .= ' ASC';
        }

        // limit
        if(isset($p['limit'])) {
            $req .= ' LIMIT '.$p['limit'];
        }

        return $this->_db->query($req, $bind);
    }

    /**
     * Count row from a table
     * 
     * $where ex:
     *  array('id','IN',array(1,2,5))
     *  array('id','=',2)
     *  'id = "2"'
     *  
     * @param  misc   
     * @return integer
     */
    public function count($where = null)
    {
        $key_to_count = (is_array($where) && isset($where[0])) ? $where[0]: $this->getPrimaryKey();
        
        $select = $this->select()->from($this->getSchemaName(), array('count('.$key_to_count.') as itemcount'));
        
        if(is_array($where)) {
            $where = $this->_db->quoteInto($where[0].' '.$where[1].' (?)',$where[2]);
            $select->where($where);
        }
        elseif(!empty($where)) $select->where($where);

        return $this->_db->fetchOne($select);
    }
    
    /**
     * Return the database schema if specified and table name together
     * ex:
     * @use    $_schema and $_name
     * @return string
     */
    public function getSchemaName()
    {
        return (!empty($this->_schema)) ? $this->_schema.'.'.$this->_name : $this->_name;
    }

    /**
     * Get default primary key string name
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        if(is_array($this->_primary)) return $this->_primary[1];
        else return $this->_primary;
    }

    /**
     * Remove unknow column keyname form an array
     *
     * @param  array $data
     * @return array
     */
    public function cleanArray($data)
    {
        //just in case zend_db have not describe table yet
        if(empty($this->_metadata)) $this->_getCols();

        //remove unknow table key
        foreach($data as $k => $v) {
            if(!array_key_exists($k, $this->_metadata)) unset($data[$k]);
            elseif(is_array($v)) {
                $data[$k] = json_encode($v); // will transfert the field value to a json
            } 
        }
        return $data;
    }

    /**
     * Insert/Update depending of the presence of primary key or not
     * Support only one primary key
     *
     * @param  array $data
     * @param  bool  $force_insert if true, will insert event if primary key is present
     * @return null|integer
     */
    public function save($data, $force_insert = false)
    {
        $data = $this->cleanArray($data);
        
        $pm = $this->getPrimaryKey();

        if(array_key_exists($pm, $data) && !empty($data[$pm]) && $force_insert === false) {

            //before update
            $this->beforeUpdate($data);

            //update
            $where = $this->_db->quoteInto($pm.' = ?',$data[$pm]);
            $this->_db->update($this->getSchemaName(), $data, $where);

            //after update
            $this->afterUpdate($data);

            return $data[$pm];
        }
        else {

            //before insert
            $this->beforeInsert($data);

            //insert
            $this->_db->insert($this->getSchemaName(), $data);
            $id = $this->_db->lastInsertId();

            //after insert
            $this->afterInsert($data, $id);

            //return the last insert id
            return $id;
        }
    }

    /**
     * This method allow inserting/updating multiple row using transaction
     *
     * @uses   method save()
     * 
     * @param  array $multiple_data
     * @return array|object Return ids inserted/updated. If a query fail, it will return the exception object
     */
    public function saveTransaction($multiple_data)
    {
        $ids = array();
        $this->_db->beginTransaction();

        try {

            // insert/update each data set with method save()
            foreach($multiple_data as $data) $ids[] = $this->save($data);

            // commit
            $this->_db->commit();
        }
        catch(Exception $e) {

            //rollback the changes
            $this->_db->rollback();

            // return exception object
            return $e;
        }

        return $ids;
    }

    /**
     * Execute stuff before insert data with method save()
     * Do nothing by default. Can be overloaded by child class.
     * 
     * @param  array $data Data to be insert
     */
    public function beforeInsert(&$data) {}

    /**
     * Execute stuff after insert data with method save()
     * Do nothing by default. Can be overloaded by child class.
     * 
     * @param  array          $data Data inserted
     * @param  string|interer $id   Represent lastInsertId() if any. 
     *                              Passed by reference, you can modify what "insert" save() method will return.
     */
    public function afterInsert($data, &$id) {}

    /**
     * Execute stuff before update data with method save()
     * Do nothing by default. Can be overloaded by child class or called manually
     * 
     * @param  array $data Data to be updated
     */
    public function beforeUpdate(&$data) {}

    /**
     * Execute stuff after update data with method save()
     * Do nothing by default. Can be overloaded by child class or called manually
     * 
     * @param  array          $data Data update
     * @param  string|interer $id   Represent lastInsertId() if any. 
     *                              Passed by reference, you can modify what "insert" save() method will return.
     */
    public function afterUpdate(&$data) {}
    
    /**
     * Instanciate and return instance of Peak_Model_Pagination
     *
     * @param  array|null $query_params 
     * @return object
     */
    public function paging($query_params = null)
    {
        if(!isset($this->_paging)) {
            $this->_paging = new Peak_Model_Pagination($this);
            if(is_array($query_params)) {
                $this->_paging->setQueryParams($query_params);
            }
        }
        return $this->_paging;
    }

    /**
     * Rearrange an array with a new key
     * Usefull to re-index your array from associative fetch
     * 
     * @param  array  $data 
     * @param  string $key  
     * @return array       
     */
    public function swapKey($data, $key)
    {
        if(!is_array($data) || count($data) < 1 || !array_key_exists($key, $data[0])) return $data;
        $final = array();
        foreach($data as $k => $v) { 
            $final[$v[$key]] = $v;
        }

        return $final;
    }

    /**
     * Rearrange an array with a new key and on value
     * Usefull to re-index your array from associative fetch to a simple list
     * 
     * @param  array  $data 
     * @param  string $key  
     * @return array       
     */
    public function swapKeyVal($data, $key, $valkey)
    {
        if(!is_array($data) || count($data) < 1 || !array_key_exists($key, $data[0]) || !array_key_exists($valkey, $data[0])) return $data;
        $final = array();
        foreach($data as $k => $v) { 
            $final[$v[$key]] = $v[$valkey];
        }

        return $final;
    }

    /**
     * Rearrange an array key&values with callbacks
     *
     * We could have wrote this method in fewer line, 
     * but we duplicate foreach to optimize the iteration for 
     * case depending on method args
     * 
     * @param  array          $data            
     * @param  string|closure $callback_key    
     * @param  closure        $callback_values 
     * @return array                 
     */
    public function swapKeyValCallback($data, $callback_key, $callback_values = null)
    {

        $final = array();

        if(!is_array($data) || count($data) < 1) return $data;

        // callback key is a string
        if(is_string($callback_key)) {
            if(!array_key_exists($callback_key, $data[0])) return $data;
            else {
                // with callback key as string and maybe callback value
                foreach($data as $k => $v) {
                    $value = (is_callable($callback_values)) ? $callback_values($k, $v) : $v;
                    $final[$v[$callback_key]] = $value;
                }

                return $final;
            }
        }
        elseif(!is_callable($callback_key)) {
            return $data;
        }

        // with callback key instead of string
        if(is_callable($callback_values)) {
            foreach($data as $k => $v) {
                // callback key and value
                $final[$callback_key($k, $v)] = $callback_values($k, $v);
            }
        }
        // with callback key with no value callback
        else {
            foreach($data as $k => $v) {
                // callback key and value
                $final[$callback_key($k, $v)] = $v;
            }
        }

        return $final;
    }
}