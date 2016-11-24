<?php
/**
 * Generate front controller
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Front extends Peak_Codegen
{
    
	/**
	 * Peak_Codegen_Class instance
	 * @var object
	 */
    public $class;
	
    /**
	 * Create front controller base with Peak_Codegen_Class 
	 */
    public function __construct()
    {
    	$this->class = new Peak_Codegen_Class();
    	
    	$this->class->setName('Front')
		            ->setExtends('Peak_Controller_Front')
		            ->docblock()->setTitle('Front Controller');   
		            
		$this->class->method('preDispatch')->docblock()->setTitle('Called before routing dispatching');
		
		$this->class->method('postDispatch')->docblock()->setTitle('Called after routing dispatching');
		
		$this->class->method('postRender')->docblock()->setTitle('Called after rendering controller view');
    }    
    
    /**
     * Generate class
     */
	public function generate()
	{
		return $this->class->generate();
	}
	
}