<?php
/**
 * Generate bootstrap class
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Codegen_Bootstrap extends Peak_Codegen
{
	
	/**
	 * Peak_Codegen_Class instance
	 * @var object
	 */
	public $class;
	
	/**
	 * Create bootstrap base with Peak_Codegen_Class 
	 */
	public function __construct()
	{
		$this->class = new Peak_Codegen_Class();
		
		$this->class->setName('Bootstrap')
		            ->setExtends('Peak_Application_Bootstrap')
		            ->docblock()->setTitle('App Bootstrapper');
	}
	
	/**
	 * Add bootstrap action shortcut
	 *
	 * @param string $action
	 */
	public function addAction($action)
	{
		$this->class->method($action)->docblock()->setTitle($action.'()');
	}
    
    /**
     * Generate class
     */
	public function generate()
	{
		return $this->class->generate();
	}
	
}