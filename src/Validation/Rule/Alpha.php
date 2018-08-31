<?php

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Alpha rule
 */
class Alpha extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptios = [
        'lower'  => true,
        'upper'  => true,
        'space'  => false,
        'french' => false,
        'punc'   => ''
    ];

    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        $regopt  = $this->buildRegexOpt();
        $regstring = '/^['.implode('', $regopt).']+$/';

        if (preg_match($regstring, $value)) {
            return true;
        }
        return false;
    }

    /**
     * Build the regex based on options
     *
     * @return array
     */
    protected function buildRegexOpt()
    {
        $regopt = [];

        if ($this->options['lower'] === true) {
            $regopt[] = 'a-z';
        }
        if ($this->options['upper'] === true) {
            $regopt[] = 'A-Z';
        }
        if ($this->options['french'] === true) {
            $regopt[] = 'À-ÿ';
        }
        if ($this->options['space'] === true) {
            $regopt[] = '\s';
        }

        if (isset($this->options['punc'])) {
            if (is_array($this->options['punc'])) {
                foreach ($this->options['punc'] as $punc) {
                    $regopt[] = '\\'.$punc;
                }
            } else {
                $punc   = $this->options['punc'];
                $strlen = strlen($punc);
                for ($i = 0; $i < $strlen; $i++) {
                    $regopt[] = '\\'.$punc{$i};
                }
            }
        }

        return $regopt;
    }
}
