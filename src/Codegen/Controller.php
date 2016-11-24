<?php
/**
 * Generate controllers classes
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Controller extends Peak_Codegen
{
    
	/**
	 * Peak_Codegen_Class instance
	 * @var object
	 */
    public $class;
	
    /**
	 * Create controller base with Peak_Codegen_Class 
	 */
    public function __construct()
    {
    	$this->class = new Peak_Codegen_Class();
    	
    	$this->class->setName('indexController')
		            ->setExtends('Peak_Controller_Action')
		            ->docblock()->setTitle('Index Controller');    	
    }
    
    /**
     * Set controller prefix class name
     *
     * @param  string $name
     * @return object
     */
    public function setName($name)
    {
    	$this->class->setName($name.'Controller')
    	            ->docblock()->setTitle($name.' Controller');
    	return $this;
    }
    
    /**
	 * Add controller action shortcut
	 *
	 * @param  string|array $action
	 * @return object
	 */
	public function addAction($action)
	{
		if(is_array($action)) {
			foreach($action as $act) {
				$act = trim($act);
				if(!empty($act)) $this->class->method('_'.$act)->docblock()->setTitle($act.' Action');
			}
		}
		else {
			if(!empty($action)) $this->class->method('_'.$action)->docblock()->setTitle($action.' Action');
		}
		return $this;
	}
	
	/**
	 * Add preAction controller method
	 *
	 * @return object
	 */
	public function addPreAction()
	{
		$this->class->method('preAction')
		            ->docblock()->setTitle('preAction() - Executed before controller handle any action');
		return $this;
	}
	
	/**
	 * Add postAction controller method
	 *
	 * @return object
	 */
	public function addPostAction()
	{
		$this->class->method('postAction')
		            ->docblock()->setTitle('postAction() - Executed after controller handle any action');
		return $this;
	}
	
	/**
	 * Add postRender controller method
	 *
	 * @return object
	 */
	public function addPostRender()
	{
		$this->class->method('postRender')
		            ->docblock()->setTitle('postRender() - Executed after a rendered action view ');
		return $this;
	}

    /**
     * Generate class
     */
	public function generate()
	{
		return $this->class->generate();
	}
	
}