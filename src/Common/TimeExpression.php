<?php

namespace Peak\Common;

use \Exception;

class TimeExpression
{
    /**
     * Time string expression
     * @var string
     */
    protected $expression;

    /**
     * Representation in seconds
     * @var int
     */
    protected $time = 0;

    /**
     * Tokens values in seconds
     * @var array
     */
    protected $tokens = [
        'ms' => 0.001, //milliseconds
        's' => 1, //seconds
        'sec' => 1, //seconds
        'min' => 60, //minute
        'h' => 3600, //hour
        'hour' => 3600, //hour
        'd' => 86400, //day
        'day' => 86400, //day
        'w' => 604800, //week
        'week' => 86400, //week
        'm' => 2592000, //month (rounded to 30 days)
        'month' => 2592000, //month (rounded to 30 days)
        'y' => 31536000, //year (rounded to 365 days)
        'year' => 31536000 //year (rounded to 365 days)
    ];

    /**
     * Selected tokens for toString()
     * @var array
     */
    protected $str_tokens = [
        'ms',
        'sec',
        'min',
        'hour',
        'day',
        'year'
    ];

    /**
     * Default string format for __toString()
     * @var string
     */
    protected $string_format = '%d %s';

    /**
     * Constructor.
     *
     * @param $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
        $this->decode();
    }

    /**
     * Transform time into readable time expression
     * @return string
     */
    public function __toString()
    {
        $time = $this->time;
        $tokens = array_reverse($this->tokens, true);
        $expression = [];

        foreach ($tokens as $token => $value) {
            if($time <= 0) {
                break;
            }
            if ($time < $value || !in_array($token, $this->str_tokens)) {
                continue;
            }
            $mod = 0;
            if (($time & $value)) {
                $mod = fmod ($time, $value);
                $time -= $mod;
            }
            $div = round($time / $value);

            $expression[] = sprintf(
                $this->string_format,
                $div,
                ($token.(($div > 1 && substr($token,-1,1) !== 's') ? 's' : ''))
            );
            $time = $mod;
        }

        $return = implode(' ', $expression);
        if (empty($return)) {
            $return = sprintf($this->string_format, 0, 'ms');
        }

        return $return;
    }

    /**
     * Shortcut of __toString + can overload string format
     *
     * @return string
     */
    public function toString($format = null)
    {
        if (isset($format)) {
            $this->string_format = $format;
        }
        return $this->__toString();
    }

    /**
     * Get time expression in milliseconds
     *
     * @return integer
     */
    public function toMicroseconds()
    {
        return $this->time * 1000;
    }

    /**
     * Get time in seconds
     *
     * @return integer
     */
    public function toSeconds()
    {
        return $this->time;
    }

    /**
     * Get date from time
     *
     * @param string $format
     * @return false|string
     */
    public function toDate($format = 'Y-m-d H:i:s')
    {
        return date($format, $this->time);
    }

    /**
     * Create regex
     *
     * @return string
     */
    protected function regexPattern()
    {
        return '#([0-9]+)('.implode('|', array_keys($this->tokens)).'){1}#i';
    }

    /**
     * Decode expression
     */
    protected function decode()
    {
        if (is_numeric($this->expression)) {
            $this->time = $this->expression;
        } elseif(is_string($this->expression)) {
            if (preg_match_all($this->regexPattern(), $this->expression, $matches)) {
                foreach ($matches[1] as $index => $value) {
                    $this->time += $this->tokens[$matches[2][$index]] * $value;
                }
            }
        } else {
            throw new Exception(__CLASS__.': Invalid time expression');
        }
    }
}
