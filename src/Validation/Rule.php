<?php

namespace Peak\Validation;

use Peak\Validation\RuleBuilder;

/**
 * Rule builder facade
 */
class Rule
{
    public static function create($name)
    {
        return new RuleBuilder($name);
    }
}
