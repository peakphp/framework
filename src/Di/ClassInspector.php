<?php

namespace Peak\Di;

use \ReflectionClass;

/**
 * Dependency Class Constructor Inspector
 */
class ClassInspector
{

    /**
     * Get constructor class dependencies
     * 
     * @param  string $class
     * @return object
     */
    public function inspect($class)
    {
        $dependencies = [];

        try {

            $r = new ReflectionClass($class);

            if($r->hasMethod('__construct')) {

                $rp = $r->getMethod('__construct')->getParameters();

                foreach($rp as $p) {

                    $prop = $p->name;

                    $dependencies[$prop] = [];
                    $dependencies[$prop]['optional'] = $p->isOptional();

                    try {
                        $class = $p->getClass();

                        if(isset($class)) {
                            $dependencies[$prop]['class'] = $class->name;
                        }
                        else {
                        }
                    }
                    catch(\ReflectionException $e) {
                        $dependencies[$prop]['error'] = $e->getMessage();
                    }
                }
            }
        }
        catch(\ReflectionException $e) {
            throw new \Exception('Can\'t resolve classname '.$class);
        }

        //print_r($dependencies);

        return $dependencies;
    }
    
}