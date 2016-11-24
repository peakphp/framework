<?php
/**
 * Peak_Model_Zendatable_Mutator
 * 
 * This class is almost the same as Peak_Model_Zendatable BUT :
 * - Class is not abstract so it can be used directly
 * - Table name, primary and schema name can be changed on the fly
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Model_Zendatable_Mutator extends Peak_Model_Zendatable
{
    
    /**
     * Constructor
     */
    public function __construct($tablename = null, $primary = null, $schema = null)
    {
        parent::__construct(); //important
        if(isset($tablename) || isset($primary) || isset($schema)) $this->change($tablename, $primary, $schema);
    }
    
    /** 
     * Change the table, primary key and schema(db name)
     *
     * @param  string|null $tablename
     * @param  string|null $primary
     * @param  string|null $schema
     * @return object      $this
     */
    public function change($tablename = null, $primary = null, $schema = null)
    {
        if(isset($tablename)) $this->_name    = $tablename;
        if(isset($primary))   $this->_primary = $primary;
        if(isset($schema))    $this->_schema  = $schema;

        // we need to reset some stuff
        $this->_cols = null;
        $this->_metadata = $this->_db->describeTable($this->_name, $this->_schema);
        $this->_getCols();

        return $this;
    }
    
    /**
	 * Public method for $_db->query()
	 *
	 * @param  string   $query
	 * @param  array    $bind
	 * @return resource
	 */
	public function query($query, $bind = array())
	{
		return $this->_db->query($query, $bind);
	}
}