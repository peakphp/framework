<?php

namespace Peak\Validation;

use Peak\Blueprint\Common\Validator;
use Peak\Validation\Definition\RuleDefinition;

/**
 * Rule builder facade
 */
class Rule implements Validator
{
    /**
     * @var Validator
     */
    private $rule;

    /**
     * @var RuleDefinition
     */
    private $ruleDefinition;

    /**
     * Rule constructor.
     *
     * @param RuleDefinition $ruleDefinition
     * @throws \Exception
     */
    public function __construct(RuleDefinition $ruleDefinition)
    {
        $this->ruleDefinition = $ruleDefinition;
        $this->rule = (new RuleBuilder($ruleDefinition->getRuleName()))
            ->setOptions($ruleDefinition->getOptions())
            ->setFlags(($ruleDefinition->getFlags()))
            ->setContext($ruleDefinition->getContext())
            ->setErrorMessage($ruleDefinition->getErrorMessage())
            ->build();
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        return $this->rule->validate($value);
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->ruleDefinition->getErrorMessage();
    }
}
