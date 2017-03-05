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
}
