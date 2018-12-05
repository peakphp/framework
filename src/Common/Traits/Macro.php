<?php

namespace Peak\Common\Traits;

use \Closure;
use \RunTimeException;

/**
 * Trait Macro
 * @package Peak\Common\Traits
 */
trait Macro
{
    /**
     * @var array
     */
    private $macros = [];

    /**
     * Add a macro
     * @param string $macroName
     * @param callable $macroCallable
     * @return $this
     */
    public function addMacro(string $macroName, Closure $macroCallable)
    {
        $this->macros[$macroName] = Closure::bind($macroCallable, $this, get_class());
        return $this;
    }

    /**
     * Add a macro
     * @param string $macroName
     * @param callable $macroCallable
     * @return $this
     */
    public function addMacroFromCallable(string $macroName, callable $macroCallable)
    {
        $closure = Closure::fromCallable($macroCallable);
        $this->macros[$macroName] = Closure::bind($closure, $this, get_class());
        return $this;
    }

    /**
     * Call a macro
     * @param string $macroName
     * @param array $args
     * @return mixed
     */
    public function callMacro(string $macroName, array $args)
    {
        if (isset($this->macros[$macroName])) {
            return call_user_func_array($this->macros[$macroName], $args);
        }
        throw new RuntimeException('There is no macro with the given name "'.$macroName.'" to call');
    }
}
