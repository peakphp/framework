<?php

declare(strict_types=1);

namespace Peak\Validation\Rule;

/**
 * Class AlphaNum
 * @package Peak\Validation\Rule
 */
class AlphaNum extends Alpha
{
    /**
     * Build the regex based on options
     *
     * @return array
     */
    protected function buildRegexOpt()
    {
        $regopt = parent::buildRegexOpt();
        $regopt[] = '0-9';
        return $regopt;
    }
}
