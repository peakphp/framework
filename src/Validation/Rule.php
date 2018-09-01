<?php

declare(strict_types=1);

namespace Peak\Validation;

use Peak\Blueprint\Common\Validator;
use Peak\Validation\Definition\RuleDefinition;

/**
 * Class Rule
 * @package Peak\Validation
 */
class Rule implements Validator
{
    /**
     * @var Validator
     */
    private $ruleValidator;

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
        $this->ruleValidator = (new RuleBuilder($ruleDefinition->getRuleName()))
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
        return $this->ruleValidator->validate($value);
    }

    /**
     * @return AbstractRule
     */
    public function getValidator()
    {
        return $this->ruleValidator;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->ruleDefinition->getErrorMessage();
    }
}
