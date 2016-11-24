<?php
/**
 * Peak Annotations
 *
 * Parse DocBlock tags of classes and methods
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_Annotations
{
    /**
     * Class reflection object
     * @var object ReflectionClass
     */ 
    protected $_class;
    
    /**
     * Class name to work on
     * @var string
     */
    protected $_class_name;
    
    /**
     * Contains list of annotation tags to be detected. 
     * By default no tags will be detected.
     * @var array|string
     */
    protected $_tags;
    
    /**
     * Setup a class to use and tags to detect
     *
     * @uses  setClass(), setTags()
     *
     * @param stirng|null $class_name
     * @param stirng|null $class_name
     */
    public function __construct($class_name = null, $tags = null)
    {
        if(isset($class_name)) $this->setClass($class_name);
        if(isset($tags)) $this->setTags($tags);
    }
    
    /**
     * Set the class name we want and load ReflectionClass
     *
     * @param  string $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->_class_name = $class;
        $this->_class = new ReflectionClass($class);
        return $this;
    }

    /**
     * Add annotation tag(s) to be detected
     *
     * @param  array|string $tagname Accept a string or an array of tags 
     *                               string '*' act as a wildcard so all tags will be detected
     * @return $this
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;
        return $this;
    }
    
    /**
     * Get a methods annotation tags
     *
     * @param  string $method_name
     * @retrun array()
     */
    public function getFromMethod($method_name)
    {
        try {
            $method = new ReflectionMethod($this->_class_name, $method_name);
        }
        catch(ReflectionException $e) {
            return array();
        }

        return $this->parse($method->getDocComment());
    }
    
    /**
     * Get all methods annotations tags 
     *
     * @return array
     */
    public function getFromAllMethods()
    {
        $a = array();
        
        foreach($this->_class->getMethods() as $m) {
            $comment = $m->getDocComment();
            $a = array_merge($a, array($m->name => $this->parse($comment)));
        }
        return $a;
    }
    
    /**
     * Get class annotation tags
     *
     * @return array
     */
    public function getFromClass()
    {
        return $this->parse($this->_class->getDocComment());
    }
    
    /**
     * Parse a doc comment string
     * with annotions tag previously specified
     *
     * @param  string $string
     * @return array
     */
    public function parse($string)
    {
        //in case we don't have any tag to detect or an empty doc comment, we skip this method
        if(empty($this->_tags) || empty($string)) return array();
   
        //check what is the type of $_tags (array|string|wildcard)
        if(is_array($this->_tags)) $tags = '('.implode('|', $this->_tags).')';
        elseif($this->_tags === '*') $tags = '[a-zA-Z0-9]';
        else $tags = '('.$this->_tags.')';
        
        //find @[tag] [params...]
        $regex = '#\* @(?P<tag>'.$tags.'+)\s+((?P<params>[\s"a-zA-Z0-9\-$\\._/-^]+)){1,}#si';
        preg_match_all($regex, $string, $matches, PREG_SET_ORDER);
        
        $final = array();
        
        if(isset($matches)) {
            
            $i = 0;
            foreach($matches as $v) {

                $final[$i] = array('tag' => $v['tag'], 'params' => array());

                //detect here if we got a param with quote or not
                //since space is the separator between params, if a param need space(s),
                //it must be surrounded by " to be detected as 1 param
                $regex = '#(("(?<param>([^"]{1,}))")|(?<param2>([^"\s]{1,})))#i';
                preg_match_all($regex, trim($v['params']), $matches_params, PREG_SET_ORDER);

                if(!empty($matches_params)) {
                    foreach($matches_params as $v) {
                        if(!empty($v['param']) && !isset($v['param2'])) {
                            $final[$i]['params'][] = $v['param'];
                        }
                        elseif(isset($v['param2']) && !empty($v['param2'])) {
                            $final[$i]['params'][] = $v['param2'];
                        }
                    }
                }
                
                ++$i;
            }
        }
        
        return $final;
    }
}