<?php

namespace Peak\Validation\Definition;

use \InvalidArgumentException;

/**
 * Class RuleArrayDefinition
 * @package Peak\Validation\Definition
 */
class RuleArrayDefinition extends RuleDefinition
{
    /**
     * RuleArrayDefinition constructor.
     *
     * @param array $definition
     */
    public function __construct(array $definition)
    {
        if (!isset($definition['ruleName'])) {
            throw new InvalidArgumentException('Rule array definition must have key "ruleName"');
        }

        if (!isset($definition['options'])) {
            $definition['options'] = [];
        }

        if (!isset($definition['flags'])) {
            $definition['flags'] = null;
        }

        if (!isset($definition['context'])) {
            $definition['context'] = null;
        }

        if (!isset($definition['errorMessage'])) {
            $definition['errorMessage'] = null;
        }

        parent::__construct(
            $definition['ruleName'],
            $definition['options'],
            $definition['flags'],
            $definition['context'],
            $definition['errorMessage']
        );
    }
}
