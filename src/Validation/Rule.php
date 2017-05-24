<?php

namespace Peak\Validation;

use Peak\Validation\RuleBuilder;

/**
 * Rule builder facade
 */
class Rule
{
    /**
     * Create an instance of RuleBuilder
     *
     * @param  string $name rule class name
     * @return Peak\Validation\RuleBuilder
     */
    public static function create($name)
    {
        return new RuleBuilder($name);
    }

    /**
     * Shorcut of create
     * Rule::[ruleName]($value, [$options [, $flags [, $context ]]]);
     *
     * @param  string $method
     * @param  array  $args
     * @return boolean
     */
    public static function __callStatic($method, $args)
    {
        $rule = new RuleBuilder(ucfirst($method));

        if (isset($args[1])) {
            $rule->setOptions($args[1]);
        }
        if (isset($args[2])) {
            $rule->setFlags($args[2]);
        }
        if (isset($args[3])) {
            $rule->setContext($args[3]);
        }

        return $rule->validate($args[0]);
    }
}
