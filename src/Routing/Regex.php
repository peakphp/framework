<?php

namespace Peak\Routing;

class Regex
{
    /**
     * Quick regex shortcut
     * @var array
     */
    private static $_quick_reg = [
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
     * Build a regex and look for quick reg syntax
     * 
     * @param string $regex the regex without the delimiters
     */
    public static function build($regex)
    {
        // look for {param_name}:validator
        // so if i got an url like http://mysite.com/editor/{id}:num
        // valid url would be ex:  http://mysite.com/editor/id/128
        $regex = preg_replace('#\{([a-zA-Z0-9_-]+)\}:([a-z]+)#', '$1/(?<$1>:$2)', $regex);

        // replace quick pattern to a standard regex expression without delimiters
        return str_ireplace(
            array_keys(self::$_quick_reg), 
            array_values(self::$_quick_reg), 
            $regex
        );
    }

    /**
     * Add a new quick reg
     * 
     * @param string $shortcut shortcut without the prefix :
     * @param string $regex    the regex without the delimiters
     */
    public static function addQuickRegex($shortcut, $regex) 
    {   
        self::$_quick_reg[':'.$shortcut] = $regex;
    }
}
