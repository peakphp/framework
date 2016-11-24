<?php
/**
 * Zend reflection wrapper specialized for Class Reflection
 * 
 * @descr    This will help you to resolve some complex treatments needed to gather informations about php classes from Zend_Reflection components.
 * 
 * @uses     Zend_Reflection classes + Zend_Loader !important
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_Zreflection
{

    /**
     * Zend_Reflection_Class object
     * @var object
     */
    public $class;

    /**
     * Load Zend_Reflection_Class
     *
     * @uses  Zend_Reflection_Class
     * 
     * @param string $class
     * @param bool   $autoload
     */
    public function loadClass($class, $autoload = true)
    {
        if(class_exists($class, $autoload)) {
            $this->class = new Zend_Reflection_Class($class);
        }
    }

    /**
     * Retreive class description 
     * 
     * @uses   Zend_Reflection_Docblock
     * @param  string $type (short or long)
     * @return string
     */
    public function getClassDoc($type = 'short')
    {
        //get short or long descr from Zend_Reflection_Docblock
        try {
            $classdoc = new Zend_Reflection_Docblock($this->class->getDocComment());
            $descr = ($type === 'short') ? $classdoc->getShortDescription() : $classdoc->getLongDescription();
        }
        catch(Exception $e) { $descr = ''; }
        return $descr;
    }

    /**
     * Retreive class description tags
     *
     * @uses   Zend_Reflection_Docblock
     * 
     * @return array
     */
    public function getClassDocTags()
    {
        //get class doc comment tags
        $result = array();
        try {
            $classDoc = new Zend_Reflection_Docblock($this->class->getDocComment());
            $tags = (is_object($classDoc)) ? $classDoc->getTags() : array();

            foreach($tags as $tag) {
                $result[] = array('name' => trim($tag->getName()),
                                  'description' => trim($tag->getDescription()));
            }

        }
        catch(Exception $e) { $result = array(); }
        return $result;
    }

    /**
     * Get class properties, parent class and interfaces
     *
     * @return array
     */
    public function getClassDeclaration()
    {
        $declaration = array();

        //class delcaration
        $properties = array();
        if($this->class->isAbstract())    $properties[] = 'abstract';
        if($this->class->isFinal())       $properties[] = 'final';
        if($this->class->isInternal())    $properties[] = 'internal';
        if($this->class->isUserDefined()) $properties[] = 'user-defined';
        $properties[] = ($this->class->isInterface()) ? 'interface' : 'class';

        $declaration['properties'] = $properties;

        //parent class
        if($this->class->getParentClass()) $declaration['parent'] = $this->class->getParentClass()->name;

        //class interface
        $interfaces = array();
        if($this->class->getInterfaces()) {
            foreach($this->class->getInterfaces() as $k => $interface) $interfaces[] = $interface->name;
        }

        $declaration['interfaces'] = $interfaces;

        return $declaration;
    }
    
    /**
     * Get class constants
     *
     * @return array
     */
    public function getConstants()
    {
        $result = array();
        $constants = $this->class->getConstants();
        foreach($constants as $k => $v) {
            $data = array('name' => $k,
                          'value' => $v);
            $result[] = $data;
        }
        
        return $result;        
    }
        
    /**
     * Get class methods, self and parents
     *
     * @return array
     */ 
    public function getMethods()
    {
        $result = array();
        $methods = $this->class->getMethods();
        foreach($methods as $m) {
            $name = $m->name;
            $data = array('name' => $name,
                          'class' => $m->getDeclaringClass()->getName(),
                          'visibility' => $this->getMethodVisibility($name),
                          'static' => $m->isStatic(),
                          'declaration' => $this->getMethodDeclaration($name),
                          'doc' => array('short' => $this->getMethodDoc($name),
                                         'long' => $this->getMethodDoc($name, 'long'),
                                         'tags' => $this->getMethodDocTags($name)),
                          'params_count' => $m->getNumberOfParameters(),
                          'params_required_count' => $m->getNumberOfRequiredParameters(),
                          'params' => $this->paramsToArray($name),
                          'params_string' => '',
                          'start_line' => $m->getStartLine(),
                          'start_line_doc' => $m->getStartLine(true),
                          'end_line' => $m->getEndLine(),
                          'body' => $m->getBody(), //seems to crash with class containing constants
                         );
            //echo $name;
            $params = $this->paramsAsList($this->class->getMethod($name)->getParameters(),array($data['class'],$name));
            $data['params_string'] = $params;
            $result[] = $data;
        }
        
        return $result;
    }
    
    /**
     * Get class parent methods only
     * Parent private method(s) can't be retreived
     *
     * @return array
     */
    public function getParentMethods()
    {
        $result = $this->getMethodsByInheritance();
        return $result['parent'];
    }
    
    /**
     * Get class self methods only
     *
     * @return array
     */
    public function getSelfMethods()
    {
        $result = $this->getMethodsByInheritance();
        return $result['self'];
    }

    /**
     * Get methods separated by inheritance(self or parent)
     *
     * @return array
     */
    public function getMethodsByInheritance()
    {
    	$result = array('self' => array(), 'parent' => array());
    	
    	$methods = $this->getMethods();
        
    	if($methods) {
            $classname = strtolower($this->class->getName());
    		foreach ($methods as $m) {
    			if(strtolower($m['class']) === $classname) $result['self'][] = $m;
    			else $result['parent'][] = $m;
    		}
    	}
    	
    	return $result;
    }
    
    /**
     * Get method decalration, also know as modifiers
     *
     * @param  string $name
     * @return array
     */
    public function getMethodDeclaration($name)
    {
        $declaration = array();
        
        $m = $this->class->getMethod($name);

        //method delcaration
        if($m->isAbstract())    $declaration[] = 'abstract';
        if($m->isStatic())      $declaration[] = 'static';
        if($m->isFinal())       $declaration[] = 'final';
        if($m->isInternal())    $declaration[] = 'internal';
        if($m->isUserDefined()) $declaration[] = 'user-defined';
        if($m->isConstructor()) $declaration[] = 'constructor';
        if($m->isDestructor())  $declaration[] = 'destructor';
        
        return $declaration;
    }
    
    /**
     * Get a class method code block
     *
     * @param  string $name              The class method nmae
     * @param  bool   $return_as_string  Return code block as a string instead of an array
     *
     * @return string|array
     */
    public function getMethodCodeBlock($name, $return_as_string = false)
    {
        // get the current filepath of class
        $file  = $this->class->getFileName();
        
        // check if this class is a file
        if(empty($file)) {
            return ($return_as_string === false) ? array() : '';
        }
        
        $start = $this->class->getMethod($name)->getStartLine();
        $end   = $this->class->getMethod($name)->getEndLine();
        $code  = array();
        $i     = 1;

        // retreive the portion of code of this method
        $fh = fopen($file, 'r');
        while(($buffer = fgets($fh, 4096)) !== false) {
            if($i >= $start && $i <= $end) $code[] = $buffer;
            elseif($i > $end) break;
            ++$i;
        }
        fclose($fh);
        
        return ($return_as_string === false) ? $code : implode("\n", $code);
    }

    /**
     * Get class declaring class name of a method 
     *
     * @param  string $name
     * @return string
     */
    public function getMethodClassname($name)
    {
        return $this->class->getMethod($name)->getDeclaringClass()->getName();
    }

    /**
     * Get method visibility
     *
     * @param  string $name
     * @return string
     */
    public function getMethodVisibility($name)
    {
        $v = array();

        if($this->class->getMethod($name)->isPublic()) $v['visibility'] = 'public';
        elseif($this->class->getMethod($name)->isPrivate()) $v['visibility'] = 'private';
        elseif($this->class->getMethod($name)->isProtected()) $v['visibility'] = 'protected';

        //if($this->class->getMethod($name)->isStatic()) $v['static'] = 'static';

        return implode(' ',$v);
    }

    /**
     * Get a method description from current class
     *
     * @uses   Zend_Reflection_Method
     * 
     * @param  string $method
     * @param  string $type (short or long)    
     * @return string
     */
    public function getMethodDoc($method, $type = 'short')
    {
    	//Method description
    	try {
    		$oMethod = new Zend_Reflection_Method($this->class->getName() ,$method);
    		$shortdescr = $oMethod->getDocblock()->getShortDescription();
    		$longdescr = $oMethod->getDocblock()->getLongDescription();

    		if($shortdescr === $longdescr) $longdescr = null;
    		elseif((empty($shortdescr)) && (!empty($longdescr))) {
    			$shortdescr = $longdescr;
    			$longdescr = null;
    		}

    	}
    	catch(Exception $e) { $shortdescr = null; $longdescr = null; }
    	
    	return ($type === 'short') ? $shortdescr : $longdescr;
    }

    /**
     * Get method comment tags
     *
     * @param  string $method
     * @return array
     */
    public function getMethodDocTags($method)
    {
        $result = array();
    	try {
			$oDocBlock = new Zend_Reflection_Docblock($this->class->getMethod($method)->getDocblock()->getContents());
			$comment_tags = $oDocBlock->getTags();
            
            foreach($comment_tags as $tag) {
                $result[] = $this->docTagsToArray($tag);
            }
		}
		catch(Exception $e) { $result = array(); }
		
		return $result;
    }
    
    /**
     * Get class properties
     *
     * @return array
     */ 
    public function getProperties()
    {
        $result = array();
        $properties = $this->class->getProperties();
        
        foreach($properties as $p) {
            $name = $p->name;
            $data = array('name' => $name,
                          'class' => $this->getPropertyClassname($name),
                          'visibility' => $this->getPropertyVisibility($name),
                          'static' => $p->isStatic(),
                          'doc' => array('short' => $this->getPropertyDoc($name),
                                         'long'  => $this->getPropertyDoc($name, 'long'),
                                         'tags'  => $this->getPropertyDocTags($name)),
                          'value' => '',
                         );

            // getting property value if we can
            // (getting value of protected and private prop is php 5.3+)
            if(!$this->class->isAbstract() || !$this->class->isInterface()) {
                $result[] = $data;
                continue;
            }
            
            $parent = $this->class->getParentClass();
            
            if(is_object($parent) && ($parent->isAbstract() || $parent->isInterface())) {
                $result[] = $data;
                continue;
            }
            // private or proctected property
            if($data['visibility'] !== 'public' && (version_compare(PHP_VERSION, '5.3.0', '>='))) {
                $prop = $this->class->getProperty($name);
                $prop->setAccessible(true);
                $data['value'] = $prop->getValue(new $data['class']());
            }
            // public property
            elseif($data['visibility'] === 'public') {
                if($p->isStatic() === true) {
                    $data['value'] = $this->class->getProperty($name)->getValue();
                }
                else {
                    $data['value'] = $this->class->getProperty($name)->getValue(new $data['class']());
                }
            }
            
            $result[] = $data;
        }
        //print_r($result);
        return $result;
    }
    
    /**
     * Get class parent properties only
     * Parent private property(ies) can't be retreived
     * 
     * @return array
     */
    public function getParentProperties()
    {
        $result = $this->getPropertiesByInheritance();
        return $result['parent'];
    }
    
    /**
     * Get class self properties only
     *
     * @return array
     */
    public function getSelfProperties()
    {
        $result = $this->getPropertiesByInheritance();
        return $result['self'];
    }

    /**
     * Get properties separated by inheritance(self or parent)
     *
     * @return array
     */
    public function getPropertiesByInheritance()
    {
    	$result = array('self' => array(), 'parent' => array());
    	
    	$props = $this->getProperties();
        
    	if($props) {
            $classname = strtolower($this->class->getName());
    		foreach ($props as $p) {
    			if(strtolower($p['class']) === $classname) $result['self'][] = $p;
    			else $result['parent'][] = $p;
    		}
    	}
    	
    	return $result;
    }
    
    /**
     * Get class declaring class name of a property 
     *
     * @param  string $name
     * @return string
     */
    public function getPropertyClassname($name)
    {
        return $this->class->getProperty($name)->getDeclaringClass()->getName();
    }

    /**
     * Get property visibility
     *
     * @param  string $name
     * @return string
     */
    public function getPropertyVisibility($name)
    {
        $v = array();

        if($this->class->getProperty($name)->isPublic()) $v['visibility'] = 'public';
        elseif($this->class->getProperty($name)->isPrivate()) $v['visibility'] = 'private';
        elseif($this->class->getProperty($name)->isProtected()) $v['visibility'] = 'protected';

        //if($this->class->getProperty($name)->isStatic()) $v['static'] = 'static';

        return implode(' ',$v);
    }

    /**
     * Get property description from the current class
     *
     * @use    ReflectionProperty, Zend_Reflection_Docblock
     * 
     * @param  string $property
     * @param  string $type
     * @return string
     */
    public function getPropertyDoc($property, $type = 'short')
    {
    	try {

    		$oProperty = new ReflectionProperty($this->class->getName(), $property);
    		$oDocblock = new Zend_Reflection_Docblock($oProperty->getDocComment());
    		$shortdescr = $oDocblock->getShortDescription();
    		$longdescr = $oDocblock->getLongDescription();

    		if($shortdescr === $longdescr) $longdescr = null;
    		elseif((empty($shortdescr)) && (!empty($longdescr))) {
    			$shortdescr = $longdescr;
    			$longdescr = '';
    		}

    	}
    	catch(Exception $e) { $shortdescr = ''; $longdescr = ''; }
    	
    	if($type === 'short') return $shortdescr;
    	else return $longdescr;
    }

    /**
     * Get property comment tags
     *
     * @param  string $property
     * @return array
     */
    public function getPropertyDocTags($property)
    {
        $result = array();
        
    	try {
    		$oProperty = new ReflectionProperty($this->class->getName(), $property);
    		$oDocblock = new Zend_Reflection_Docblock($oProperty->getDocComment());
			$comment_tags = $oDocblock->getTags();
            
            foreach($comment_tags as $tag) {
                $result[] = $this->docTagsToArray($tag);
            }
		}
		catch(Exception $e) { $result = array(); }
		
		return $result;
    }

    /**
     * Get params object as list
     *
     * @uses   Zend_Reflection_Parameter
     * 
     * @param  object $params
     * @param  array  $router 
     * @return string
     */
    public function paramsAsList($paramsObject, $router)
    {
        $params_list = array();

        foreach($paramsObject as $param) {

            //tr to retreive param type
            try {
                $p = new Zend_Reflection_Parameter($router,$param->name);
                $param->type = $p->getType();
                $param->type = (empty($param->type)) ? '' : $param->type.' ';
                if($p->isPassedByReference()) $param->type = $param->type.'&';
            }
            catch(Exception $e) { $param->type = ''; }

            $params_list[] = ($param->isOptional()) ? '['.$param->type.' <strong><i>$'.$param->name.'</i></strong>]' : $param->type.' <strong><i>$'.$param->name.'</i></strong>';
        }
        return implode(', ',$params_list);
    }

    /**
     * Get params object as simple array
     *
     * @param  string $method
     * @return array
     */
    public function paramsToArray($method)
    {
        $params = $this->class->getMethod($method)->getParameters();
        $paramsArray = array();
        foreach($params as $param) {
            $paramsArray[] = $param->name;
        }
        return $paramsArray;
    }

    /**
     * Get tags object as array
     *
     * @param  array|object $tags
     * @return array
     */
    public function docTagsToArray($tags)
    {
        $result = array();
    	$fields = array('name' => '','type' => '', 'variable' => '', 'description' => '');
        
        $tags_array = (!is_array($tags)) ? array($tags) : $tags;

        foreach($tags_array as $t) {
            $tag = $fields;
            if(method_exists($t,'getName')) $tag['name'] = trim($t->getName());
            if(method_exists($t,'getType')) $tag['type'] = trim($t->getType());
            if(method_exists($t,'getVariableName')) $tag['variable'] = trim($t->getVariableName());
            if(method_exists($t,'getDescription')) $tag['description'] = trim($t->getDescription());
            $result[] = $tag;
        }
        if(empty($result)) $result[] = $fields;

        if(!is_array($tags)) return $result[0];
    	else return $result;
    }
}