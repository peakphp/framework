<?php

namespace Peak\Validation;

use Peak\Validation\RuleBuilder;

/**
 * Rule builder facade
 */
class Rule
{
    static function create($name)
    {
        return new RuleBuilder($name);
    }
}
