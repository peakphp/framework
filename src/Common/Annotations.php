<?php

declare(strict_types=1);

namespace Peak\Common;

use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionException;

/**
 * Annotations
 * Parse DocBlock tags of classes and methods
 */
class Annotations
{
    /**
     * Class reflection object
     * @var object ReflectionClass
     */
    protected $class;
    
    /**
     * Class name to work on
     * @var string
     */
    protected $classname;
    

    /**
     * Setup a class to use
     *
     * @param mixed $class
     */
    public function __construct($class)
    {
        $this->setClass($class);
    }
    
    /**
     * Set the class name we want and load ReflectionClass
     *
     * @param  mixed $class Class name or class instance
     */
    protected function setClass($classname): void
    {
        $this->classname = $classname;
        if (is_object($classname)) {
            $this->classname = get_class($classname);
        }
        $this->class = new ReflectionClass($classname);
    }

    /**
     * Get a methods annotation tags
     *
     * @param  string $method_name
     * @param  string|array  $tags   Tag(s) to retrieve, by default($tags = '*'), it look for every tags
     * @return array
     */
    public function getMethod($method_name, $tags = '*'): array
    {
        try {
            $method = new ReflectionMethod($this->classname, $method_name);
        } catch (ReflectionException $e) {
            return [];
        }

        return $this->parse($method->getDocComment(), $tags);
    }
    
    /**
     * Get all methods annotations tags
     *
     * @param  mixed   $tags   Tag(s) to retrieve, by default($tags = '*'), it look for every tags
     * @return array
     */
    public function getAllMethods($tags = '*'): array
    {
        $a = [];
        
        foreach ($this->class->getMethods() as $m) {
            $comment = $m->getDocComment();
            $a = array_merge($a, [$m->name => $this->parse($comment, $tags)]);
        }
        return $a;
    }
    
    /**
     * Get class annotation tags
     *
     * @param  mixed  $tags   Tag(s) to retrieve, by default($tags = '*'), it look for every tags
     * @return array
     */
    public function getClass($tags = '*'): array
    {
        return $this->parse($this->class->getDocComment(), $tags);
    }
    
    /**
     * Parse a doc comment string
     * with annotations tag previously specified
     *
     * @param  string        $string Docblock string to parse
     * @param  string|array  $tags   Tag(s) to retrieve, by default($tags = '*'), it look for every tags
     * @return array
     */
    public static function parse($string, $tags = '*'): array
    {
        //in case we don't have any tag to detect or an empty doc comment, we skip this method
        if (empty($tags) || empty($string)) {
            return [];
        }
   
        //check what is the type of $tags (array|string|wildcard)
        if (is_array($tags)) {
            $tags = '('.implode('|', $tags).')';
        } elseif ($tags === '*') {
            $tags = '[a-zA-Z0-9]';
        } else {
            $tags = '('.$tags.')';
        }
        
        //find @[tag] [params...]
        $regex = '#\* @(?P<tag>'.$tags.'+)\s+((?P<data>[\s"a-zA-Z0-9\-$\\._/-^]+)){1,}#si';
        preg_match_all($regex, $string, $matches, PREG_SET_ORDER);
        
        $final = [];
        
        if (isset($matches)) {
            $i = 0;
            foreach ($matches as $v) {
                $final[$i] = array('tag' => $v['tag'], 'data' => []);

                //detect here if we got a param with quote or not
                //since space is the separator between params, if a param need space(s),
                //it must be surrounded by " to be detected as 1 param
                $regex = '#(("(?<param>([^"]{1,}))")|(?<param2>([^"\s]{1,})))#i';
                preg_match_all($regex, trim($v['data']), $matches_params, PREG_SET_ORDER);

                if (!empty($matches_params)) {
                    foreach ($matches_params as $v) {
                        if (!empty($v['param']) && !isset($v['param2'])) {
                            $final[$i]['data'][] = $v['param'];
                        } elseif (isset($v['param2']) && !empty($v['param2'])) {
                            $final[$i]['data'][] = $v['param2'];
                        }
                    }
                }
                
                ++$i;
            }
        }
        
        return $final;
    }
}
