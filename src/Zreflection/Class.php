<?php
/**
 * Gather all data about a class just by loading it
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Zreflection_Class extends Peak_Zreflection
{
    // class
    public $class_name;
    public $class_declaration;
    public $class_doc_short;
    public $class_doc_long;
    public $class_doc_tags;
    
    // constants
    public $constants = array();
    
    // methods
    public $self_methods  = array();
    public $parent_methods  = array();
    
    // properties
    public $self_properties  = array();
    public $parent_properties = array();
    
    /**
     * Overload parent method
     */
    public function loadClass($class, $autoload = true)
    {
        parent::loadClass($class, $autoload);
        $this->_fetch();
    }
    
    /**
     * Fetch all infos of loaded class 
     */
    private function _fetch()
    {
        // class
        $this->class_name = $this->class->getName();
        $this->class_declaration = $this->getClassDeclaration();
        $this->class_doc_short = $this->getClassDoc();
        $this->class_doc_long = $this->getClassDoc('long');
        $this->class_doc_tags = $this->getClassDocTags();
        
        // constants
        $this->constants = $this->getConstants();
        
        // Define the custom sort function
        function custom_sort($a,$b) {
             return $a['name']>$b['name'];
        }
        
        // methods
        $this->self_methods = $this->getSelfMethods();
        $this->parent_methods = $this->getParentMethods();
        
        // sorting callback func
        usort($this->self_methods, 'custom_sort');
        usort($this->parent_methods, 'custom_sort');
                
        // properties
        $this->self_properties = $this->getSelfProperties();
        $this->parent_properties = $this->getSelfProperties();
        
        // sorting callback func
        usort($this->self_properties , 'custom_sort');
        usort($this->parent_properties, 'custom_sort');
    }
}