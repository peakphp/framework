<?php

namespace Peak\Di;

use Peak\Di\Container;
use Peak\Di\ClassInspector;
use Peak\Di\InterfaceResolver;

/**
 * Dependency Class Resolver
 */
class ClassResolver
{
    /**
     * ClassInspector
     * @var object
     */
    protected $inspector;

    /**
     * InterfaceResolver
     * @var object
     */
    protected $iresolver;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inspector = new ClassInspector();
        $this->iresolver = new InterfaceResolver();
    }

    /**
     * Resolve class arguments dependencies
     * 
     * @param  string $class
     * @return object
     */
    public function resolve($class, Container $container, array $args = [], $explicit = [])
    {
        $dependencies = $this->inspector->inspect($class);
        $class_args   = [];
        $class_count  = 0;
        $i            = 0;

        foreach($dependencies as $key => $d) {

            if(isset($d['error'])) {
                throw new \Exception($d['error']);
            }

            if(isset($d['class'])) {

                $name = $d['class'];
                ++$class_count;

                if($container->hasInstance($name)) {
                    $class_args[] = $container->getInstance($name);
                }
                else {

                    // dealing with an interface dependency
                    if(interface_exists($name)) {
                        $class_args[] = $this->iresolver->resolve($name, $container, $explicit);
                    }
                    // or resolve dependency by instanciate object classname
                    else {
                        $child_args = [];
                        if(array_key_exists($name, $args)) {
                            $child_args = $args[$name];
                        }
                        $class_args[] = $container->instantiate($name, $child_args, $explicit);
                    }
                }
            }
            else if(array_key_exists($i - ($class_count), $args)) {
                $class_args[] = $args[$i - $class_count];
            }

            ++$i;
        }

        return $class_args;
    } 
}