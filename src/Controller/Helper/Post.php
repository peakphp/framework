<?php
/**
 * Facilitate access/sanitazation of $_POST in your controller
 *
 * @uses    Peak_Config
 * @author  Francois Lajoie
 * @version $Id: post.php 366 2011-05-29 04:15:16Z snake386@hotmail.com $
 */
class Peak_Controller_Helper_Post extends Peak_Config
{

    /**
     * Inject $_POST in class
     */
    public function __construct()
    {
        $this->setVars($_POST);
    }

    /**
     * Flush $_POST data
     *
     * @return object
     */
    public function flushPost()
    {
        $_POST = null;
        return $this;
    }

    /**
     * Filter a key
     *
     * @param  string  $key
     * @param  integer $filter
     * @param  array   $options
     * @return object
     */
    public function filter($key, $filter, $options)
    {
        if(isset($this->_vars[$key])) {
            $this->_vars[$key] = filter_var($this->_vars[$key], $filter, $options);
        }
        return $this;
    }

    /**
     * Filter all variables
     *
     * @param  integer $filter
     * @param  array   $options
     * @return object
     */
    public function filterAll($filter, $options)
    {
        $vars = $this->_filterRecursive($filter, $options, $this->getVars());
        $this->setVars($vars);
        return $this;
    }
    
    /**
     * Filter recursively ( used by filterAll() and filterAllAsString() )
     *
     * @param  integer|constant $filter
     * @param  array $options
     * @param  array $array
     * @return array
     */
    private function _filterRecursive($filter, $options, $array) 
    {
        foreach($array as $key => $val) {
        
            if(is_array($val)) {
                $array[$key] = $this->_filterRecursive($filter, $options, $val);
            }
            else $array[$key] = filter_var($val, $filter, $options);
        }
        return $array;
    }
    
    

    /**
     * Filter all variables as string by stripping tags and special characters.
     *
     * @return object
     */
    public function filterAllAsString()
    {
        $this->filterAll(FILTER_SANITIZE_STRING, array('flags'  => FILTER_FLAG_STRIP_HIGH && FILTER_FLAG_STRIP_LOW));
        return $this;
    }
}