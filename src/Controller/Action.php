<?php
/**
 * Peak abstract action controller
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
abstract class Peak_Controller_Action
{
    /**
     * view script file to render
     * @var string
     */
    public $file;

    /**
     * view scripts controller absolute path
     * @var string
     */
    public $path;

    /**
     * action called by dispatchAction()
     * @var string
     */
    public $action;
	
	/**
     * instance of view
     * @var object
     */
    public $view;
    
    /**
     * Action method prefix
     * @var string
     */
    protected $action_prefix = '_';

    /**
     * controller helpers objects
     * @var object
     */
    protected $helpers;

    /**
     * request params array
     * @var array
     */
    protected $params;

    /**
     * request params associative array
     * @var array
     */
    protected $params_assoc;
	
	/**
	 * dispatch action with argument
	 * @var bool
	 */
	protected $actions_with_params = true;


    public function __construct()
    {   
        //initialize ctrl
        $this->initController();
        //get route to dispatch
        $this->getRoute();
    }
    
    /**
     * Try to return a helper object based the method name.
     *
     * @param  string $helper
     * @param  null   $args not used
     * @return object
     */
    public function __call($helper, $args = null)
    {
    	if((isset($this->helper()->$helper)) || ($this->helper()->exists($helper))) {
        	return $this->helper()->$helper;
        }
        elseif(defined('APPLICATION_ENV') && in_array(APPLICATION_ENV, array('development', 'testing'))) {
            trigger_error('Controller method/helper '.$helper.'() doesn\'t exists');
        }
    }

    /**
     * Initialize controller $name, $title, $path, $url_path and $type
     * @final
     */
    final private function initController()
    {       
        $this->view = Peak_Registry::o()->view; 
  
        $this->path = Peak_Core::getPath('theme_scripts').'/'.$this->getTitle();
    }
    
    /**
     * Get controller class name
     *
     * @return string
     */
    public function getName()
    {
        return get_class($this);
    }
    
    /**
     * Get controller class title
     * 
     * @return string
     */
    public function getTitle()
    {
        if(preg_match('#^Peak_Controller_Internal_[a-zA-Z_-]*$#', $this->getName())) {
            return str_replace('Peak_Controller_Internal_','',$this->getName());
        }
        else return str_ireplace('controller', '', $this->getName());  
    }

    /**
     * Get view scripts absolute path
     * @return string
     */
    public function getScriptsPath()
    {
        return Peak_Core::getPath('theme_scripts').'/'.$this->getTitle();
    }
        
    /**
     * Get current action method name
     *
     * @return string
     */
    public function getAction($noprefix = false)
    {
        if($noprefix) {
            return substr($this->action, 1);
        }

        return $this->action;
    }
    
    /**
     * Get array of controller "actions"(methods)
     *
     * @return  array
     */
    public function getActions()
    {
    	$actions = array();
    	
        $c_methods = get_class_methods($this);

        $regexp = '/^(['.$this->action_prefix.']{'.strlen($this->action_prefix).'}[a-zA-Z]{1})/';
              
        foreach($c_methods as $method) {            
            if(preg_match($regexp,$method)) $actions[] = $method;
        }

        return $actions;
    }
    
    /**
     * Get data from router needed for dispatch
     */
    public function getRoute()
    {
        $this->params       = Peak_Registry::o()->router->params;        
        $this->params_assoc = new Peak_Config(Peak_Registry::o()->router->params_assoc);
        $this->action       = $this->action_prefix . Peak_Registry::o()->router->action;
        //set default ctrl action if none present
        if($this->action === $this->action_prefix) $this->action  = $this->action_prefix.'index';
    }    
    
    /**
     * Dispatch controller action and other stuff around it
     */
    public function dispatch()
    {
        $this->preAction();
        $this->dispatchAction();
        $this->postAction();
    }
    
    /**
     * Dispatch action requested by router or the default action(_index)
     */
    public function dispatchAction()
    { 
        if($this->isAction($this->action) === false) {
            throw new Peak_Controller_Exception('ERR_CTRL_ACTION_NOT_FOUND', array($this->action, $this->getName()));
        }

        //set action filename
        if($this->action_prefix === '_') $this->file = substr($this->action,1).'.php';
        else $this->file = str_replace($this->action_prefix, '',$this->action).'.php';

        //call requested action
		if($this->actions_with_params) {
			$this->dispatchActionParams($this->action);
		}
		else{
            $method = $this->action;
            $this->$method(); 
        } 
    }
	
	/**
	 * Check action methods agrs needed and call it properly
	 *
	 * @param string $action_name
	 */
	private function dispatchActionParams($action_name)
	{
		//get action params
		$zf = new Peak_Zreflection();
		$zf->loadClass($this->getName());
		$params = $zf->class->getMethod($action_name)->getParameters();
		
		//fetch request params with action params
		$args = array();
		$errors = array();
		if(!empty($params)) {
			foreach($params as $p) {
				$pname = $p->name;
				if(isset($this->params()->$pname)) $args[] = $this->params()->$pname;
				elseif($p->isOptional()) $args[] = $p->getDefaultValue();
				else $errors[] = '$'.$pname;
			}
		}
		
		//if we got errors(param missing), we throw an exception
		if(!empty($errors)) {
			throw new Peak_Controller_Exception('ERR_CTRL_ACTION_PARAMS_MISSING', array(count($errors), $this->getName().'::'.$action_name.'()'));
		}
		
		//call action with args
		return call_user_func_array(array($this, $action_name), $args);
	}

    /**
     * Check if action method name exists
     *
     * @param  string $name
     * @return bool
     */
    public function isAction($name)
    {
    	return (method_exists($this, $name)) ? true : false;
    }

    /**
     * Load/access to controllers helpers objects
     * 
     * @return object Peak_Controller_Helpers
     */
    public function helper()
    {
        if(!is_object($this->helpers)) $this->helpers = new Peak_Controller_Helpers();
    	return $this->helpers;
    }
    

    /**
     * Instanciate models. Examples :
     *
     *  $page = $this->model('test/model')  is the same as $mymodel = new App_Models_Test_Model();
     *  $this->model('test/model', null, 'mymodel')  is the same as $this->mymodel = new App_Models_Test_Model();
     *  $this->model('mypath/model', $myname) is the same as $model = new App_Models_Test_Model($myname);
     *  $this->model('mypath/model', $myname, 'mymodel') is the same as $this->mymodel = new App_Models_Test_Model($myname);
     *   
     *
     * @param  string      $model_path
     * @param  misc|null   $params (new in 0.9.5, note $class_varname and $params order have been inversed)
     * @param  string|null $class_varname
     * @return object      return object if $varname is null
     */
    public function model($model_path, $class_varname = null, $params = null)
    {
        $model = str_replace('/','_',$model_path);
        $class = 'App_Models_'.$model;
        if(isset($class_varname)) {
			$this->$class_varname = (!is_null($params)) ? new $class($params): new $class();
            return $this;
        }
        else {
			return (!is_null($params)) ? new $class($params): new $class();
		}
    }

    /**
     * Access to params_assoc object
     *
     * @return object
     */
    public function params()
    {
        return $this->params_assoc;
    }    

    /**
     * Call view render with controller $file and $path
     *
     * @return string
     */    
    public function render()
    {                
        $this->view->render($this->file, $this->path);     
        $this->postRender();
    }

    /**
     * Call front controller redirect() method
     *
     * @param string     $ctrl
     * @param string     $action 'index' by default
     * @param array|null $params
     */
    public function redirect($ctrl, $action = 'index', $params = null)
    {
        Peak_Registry::o()->app->front->redirect($ctrl, $action, $params);
    }
    
    /**
     * Call front controller redirect() method. 
     * Same as redirect() but redirect to an action in the current controller only
     *
     * @param string     $action
     * @param array|null $params
     */
    public function redirectAction($action, $params = null)
    {
        $this->redirect($this->getTitle(), $action, $params);
    }
	
	/**
	 * Use View helper "redirect" to make a HTTP header redirection
	 *
	 * @param string  $url
	 * @param bool    $base_url
	 * @param integer $http_code
	 */
	public function redirectUrl($url, $http_code = 302, $base_url = true)
	{
		if($base_url) $url = $this->view->baseUrl($url,true);
		$this->view->header()->redirect($url, $http_code);
	}

    /**
     * Action before controller requested action
     */
    public function preAction() { }

    /**
     * Action after controller requested action
     */
    public function postAction() { }

    /**
     * Action after view rendering
     */
    public function postRender() { }
}