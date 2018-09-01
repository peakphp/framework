<?php

declare(strict_types=1);

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
        if (!isset($definition['rule'])) {
            throw new InvalidArgumentException('Rule array definition must have key "rule"');
        }

        if (!isset($definition['options'])) {
            $definition['options'] = [];
        } elseif (!is_array($definition['options'])) {
            throw new InvalidArgumentException('Rule array definition key "options" must be an array');
        }

        if (!isset($definition['flags'])) {
            $definition['flags'] = null;
        } elseif (!is_integer($definition['flags'])) {
            throw new InvalidArgumentException('Rule array definition key "flags" must be an integer');
        }

        if (!isset($definition['context'])) {
            $definition['context'] = null;
        }

        if (!isset($definition['error'])) {
            $definition['error'] = '';
        } elseif (!is_string($definition['error'])) {
            throw new InvalidArgumentException('Rule array definition key "error" must be an string');
        }

        parent::__construct(
            $definition['rule'],
            $definition['options'],
            $definition['flags'],
            $definition['context'],
            $definition['error']
        );
    }
}
