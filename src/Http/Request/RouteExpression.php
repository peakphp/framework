<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use function array_keys;
use function array_values;
use function preg_replace;
use function str_replace;
use function strpos;
use function substr;

class RouteExpression
{
    /**
     * @var string
     */
    private $expression;

    /**
     * @var string
     */
    private $regex;

    /**
     * @var array
     */
    private $regexExpression = [
        ':any'       => '[^\/]+',
        ':negnum'    => '-[0-9]+',
        ':posnum'    => '[0-9]+',
        ':num'       => '-?[0-9]+',
        // for float pattern, string like .5 is not valid, it must be 0.5
        ':negfloat'  => '-([0-9]+\.[0-9]+|[0-9]+)',
        ':posfloat'  => '([0-9]+\.[0-9]+|[0-9]+)',
        ':float'     => '[-+]?([0-9]+\.[0-9]+|[0-9]+)',
        // chars, numbers, -, _ and + only
        ':permalink' => '[a-zA-Z0-9+_-]+',
        ':alphanum'  => '[a-zA-Z0-9]+',
        ':alpha'     => '[a-zA-Z]+',
        ':year'      => '[12][0-9]{3}',            // 1000 to 2999
        ':month'     => '0[1-9]|1[012]|[1-9]',     // valid ex: 07, 7, 12, 31
        ':day'       => '[12][0-9]|3[01]|0?[1-9]', // valid ex: 2, 12, 02, 15, 31
    ];

    /**
     * RouteExpression constructor.
     * @param string $expression
     */
    public function __construct(string $expression)
    {
        $this->expression = $expression;
        $this->regex = $expression;
        $this->compile();
    }

    /**
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * Compile expression to valid regex
     */
    private function compile()
    {
        // replace pseudo {param}:type syntax to valid regex
        if (strpos($this->regex, '}:') !== false) {
            $this->regex = preg_replace('#\{([a-zA-Z0-9_-]+)\}:([a-z]+)#', '(?P<$1>:$2)', $this->expression);
            $this->regex = str_replace(
                array_keys($this->regexExpression),
                array_values($this->regexExpression),
                $this->regex
            );
        }

        // replace pseudo {param} syntax without type to valid regex
        if (strpos($this->regex, '}') !== false) {
            $this->regex = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^\/]+)', $this->regex);
        }

        // allow optional trailing slash at the end
        if (substr($this->regex, -1) !== '/') {
            $this->regex .= '[\/]?';
        }
    }
}