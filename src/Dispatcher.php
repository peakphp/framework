<?php

/**
 * Peak Dispatcher
 * 
 * This is an standalone component and should not be used inside framework MVC!
 * This class looks for actions keys names in global variable like $_GET, $_POST, $_SESSION and 
 * dispatch them to action(s) depending on $_recursive_depth properties. 
 * Usefull for standalone page or to regroup logic behind ajax request
 * IMPORTANT. The data of action value and $resource are not filtered so be sure to sanitize/valid action 
 * datas and resource before doing anything 
 *  
 * @author  Francois Lajoie
 * @version $Id$
 */
abstract class Peak_Dispatcher
{

	/**
	 * always reflect current resource of a called action
	 * @var array
	 */
    public $resource;

    /**
     * container for response
     * @var array
     */
    public $response = array();

    /**
     * global variable allow
     * @var array
     */
    private $_accepted_globals = array('_GET','_POST','_SESSION');

    /**
     * actions method list depending on $_accepted_globals
     * @var array
     */
    private $_actions = array();

    /**
     * allow multiple actions calls. if isset to false, its first in first out
     * @var bool
     */
    private $_recursivity  = false;

    /**
     * define the maximum of actions that can be called in the hole process
     * @var integer
     */
    private $_recursivity_depth = 3;
   
    /**
     * number of actions called
     * @var integer
     */
    private $_actions_triggered = 0;


    /**
     * Load dispatcher actions
     */
    public function __construct($accepted_globals = null, $recursivity = false, $recursivity_depth = 3)
    {
        if(isset($accepted_globals)) $this->_accepted_globals = $accepted_globals;
        $this->setRecursivity($recursivity, $recursivity_depth);
        $this->_listActions();
    }
    
    /**
     * Start the first in, first out action(s) dispath. 
     */
    public function start()
    {
        foreach($this->_accepted_globals as $prefix) 
        {            
            switch($prefix) {
                case '_GET' : $this->resource = $_GET; break;
                case '_POST' : $this->resource = $_POST; break;
                case '_SESSION' : 
                     if(session_id() !== '') $this->resource = $_SESSION; 
                     else $this->resource = null;
                     break;
                default : $this->resource = null;
            }
            
            if(is_array($this->resource))
            {                
                foreach($this->_actions as $action)
                {                   
                    $action_key = str_ireplace($prefix.'_','',$action);                        
                    if(isset($this->resource[$action_key])) {
                    	++$this->_actions_triggered;
                        $this->$action();
                        if(!$this->_recursivity) {
                            $this->stop();
                            return;
                        }
                        else {                           
                            if($this->_actions_triggered >= $this->_recursivity_depth) {                        
                                $this->stop();
                                return;
                            }
                        }
                        
                    }
                }
            }
        }
    }
    
    /**
     * Stop recursivity
     *
     */
    public function stop()
    {
        $this->_recursivity = false;
        $this->resource = null;
    }

    /**
     * Reset object to default value
     */
    public function reset()
    {
    	$this->_actions_triggered = 0;
    	$this->_recursivity = false;
    	$this->_recursivity_depth = 3;
    	$this->_accepted_globals = array('_GET','_POST','_SESSION');
        $this->resource = null;
        $this->response = array();
        $this->_listActions();
    }

    /**
     * Set Recursion to true/false
     *
     * @param bool $status
     */
    public function setRecursivity($status,$depth = 3)
    {
        $this->_recursivity = $status;
        $this->_recursivity_depth = $depth;        
    }

    /**
     * List all actions regarding $_accepted_globals
     */
    private function _listActions()
    {       
        $regexps = array();
        
        foreach($this->_accepted_globals as $prefix) {
            $l = strlen($prefix) + 1;
            $regexps[] = '/^(['.$prefix.'_]{'.$l.'}[a-zA-Z]{1})/';
        }
        
        $c_methods = get_class_methods(get_class($this));
        
        $this->_actions = array();
        
        if(!is_null($c_methods)) {
            foreach($c_methods as $method) {
                foreach($regexps as $regexp) {
                    if(preg_match($regexp,$method)) {
                        $this->_actions[] = $method; break;
                    }
                }
            }
        }
    }

    /**
     * Return actions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->_actions;
    }

    /**
     * Return accepted globals
     * 
     * @return array
     */
    public function getAcceptedGlobals()
    {
    	return $this->_accepted_globals;
    }

    /**
     * Return recursivity
     * 
     * @return array
     */
    public function getRecursivity()
    {
    	return $this->_recursivity;
    }

    /**
     * Return recursivity depth
     * 
     * @return array
     */
    public function getRecursivityDepth()
    {
    	return $this->_recursivity_depth;
    }

    /**
     * Return number of actions triggered
     * 
     * @return array
     */
    public function getActionsTriggered()
    {
    	return $this->_actions_triggered;
    }
}