<?php

namespace Peak\Validation\Rules;


use Peak\Validation\Rules\Alpha;

/**
 * Alpha numeric rule
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
