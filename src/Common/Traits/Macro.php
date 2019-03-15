<?php

namespace Peak\Common\Traits;

use \Closure;
use \RuntimeException;

use function call_user_func_array;
use function get_class;

trait Macro
{
    /**
     * @var array
     */
    private $macros = [];

    /**
     * @param string $name
     * @param Closure $macroCallable
     * @return $this
     */
    public function addMacro(string $name, Closure $macroCallable)
    {
        $this->macros[$name] = Closure::bind($macroCallable, $this, get_class());
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasMacro(string $name)
    {
        return isset($this->macros[$name]);
    }

    /**
     * Call a macro
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function callMacro(string $name, array $args)
    {
        if (isset($this->macros[$name])) {
            return call_user_func_array($this->macros[$name], $args);
        }
        throw new RuntimeException('There is no macro with the given name "'.$name.'" to call');
    }
}
