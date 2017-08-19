<?php

namespace Peak\Common;

use \DateInterval;
use \DateTime;
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
     * Internal DateInternal instance
     * @var DateInterval
     */
    protected $di;

    /**
     * Tokens values in seconds
     * @var array
     */
    protected $tokens_values = [
        'ms' => 0.001, //milliseconds
        's' => 1, //seconds
        'sec' => 1, //seconds
        'm' => 60, //minute
        'min' => 60, //minute
        'h' => 3600, //hour
        'hour' => 3600, //hour
        'd' => 86400, //day
        'day' => 86400, //day
        'w' => 604800, //week
        'week' => 86400, //week
        'month' => 2592000, //month (rounded to 30 days)
        'y' => 31536000, //year (rounded to 365 days)
        'year' => 31536000 //year (rounded to 365 days)
    ];

    /**
     * DateInterval tokens
     * @var array
     */
    protected static $tokens = [
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];

    /**
     * DateTokens
     * @var array
     */
    protected static $tokens_substitution = [
        'i' => 'm',
    ];

    /**
     * Constructor
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
     *
     * @return string
     */
    public function __toString()
    {
        $format = '';

        if (empty($this->time)) {
            return '0 second';
        }

        foreach (self::$tokens as $token => $title) {
            if ($this->di->$token > 0) {
                $format .= '%'.$token.' '.$title.(($this->di->$token < 2) ? '' : 's'). ' ';
            }
        }

        return trim((new DateTime('@0'))
            ->diff(new DateTime('@'.$this->time))
            ->format($format));
    }

    /**
     * Shortcut of __toString + can overload string format
     *
     * @return string
     */
    public function toString($format = null)
    {
        if (isset($format)) {
            return (new DateTime('@0'))
                ->diff(new DateTime('@'.$this->time))
                ->format($format);
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
     * @return mixed
     */
    public function toDate($format = 'Y-m-d H:i:s')
    {
        return date($format, $this->time);
    }

    /**
     * Get internal DateInterval instance
     *
     * @return DateInterval
     */
    public function toDateInterval()
    {
        return $this->di;
    }

    /**
     * Get ISO8601 interval spec string
     *
     * @return string
     */
    public function toIntervalSpec()
    {
        return self::dateIntervalToIntervalSpec($this->di);
    }

    /**
     * Transform a DateInterval to a valid ISO8601 interval
     *
     * @param DateInterval $di
     * @return string
     */
    public static function dateIntervalToIntervalSpec(DateInterval $di)
    {
        $time_parts = ['h', 'i', 's', 'f'];
        $time_token_set = false;
        $interval_spec = 'P';

        foreach (array_keys(self::$tokens) as $token) {
            if (in_array($token, $time_parts) && $time_token_set === false && $di->$token > 0) {
                $interval_spec .= 'T';
                $time_token_set = true;
            }
            if ($di->$token > 0) {
                $token_string = $token;
                if (array_key_exists($token, self::$tokens_substitution)) {
                    $token_string = self::$tokens_substitution[$token];
                }
                $interval_spec .= $di->$token.strtoupper($token_string);
            }
        }

        return $interval_spec;
    }

    /**
     * Decode expression
     */
    protected function decode()
    {
        $error = false;

        if (empty($this->expression)) {
            return;
        }

        if (is_numeric($this->expression)) {
            $this->expression = $this->integerToString($this->expression);
        }

        try {
            $this->decodeIntervalSpec();
        } catch (Exception $e) {
            $error = true;
        }

        if ($error) {
            $error = false;
            try {
                $this->decodeTimeString();
            } catch (Exception $e) {
                $error = true;
            }

            // last resort
            if ($error) {
                $error = false;
                if ($this->decodeRegexString() === false) {
                    $error = true;
                }
            }
        }

        if ($error) {
            throw new Exception(__CLASS__.': Invalid time expression');
        }
    }

    /**
     * Decode a DateInterval Interval spec format
     */
    protected function decodeIntervalSpec()
    {
        $this->di = new DateInterval($this->expression);
        $this->time = (new DateTime('@0'))
            ->add($this->di)
            ->getTimestamp();
    }

    /**
     * Decode DateInterval string format (Uses the normal php date parsers)
     */
    protected function decodeTimeString()
    {
        $di = DateInterval::createFromDateString($this->expression);
        $this->time = (new DateTime('@0'))
            ->add($di)
            ->getTimestamp();

        // force verification
        $this->di = new DateInterval($this->dateIntervalToIntervalSpec($di));
    }

    /**
     * Decode string with customs regex formats
     *
     * @return bool
     */
    protected function decodeRegexString()
    {
        $regex_string = '#([0-9]+)[\s]*('.implode('|', array_keys($this->tokens_values)).'){1}#i';
        $regex_clock_string = '#^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$#i';

        if (preg_match_all($regex_string, $this->expression, $matches)) {
            foreach( $matches[1] as $index => $value) {
                $this->time += $this->tokens_values[$matches[2][$index]] * $value;
            }
            $this->di = DateInterval::createFromDateString($this->integerToString($this->time));
            return true;
        } elseif (preg_match_all($regex_clock_string, $this->expression, $matches) && count($matches) == 4) {
            if (!empty($matches[1][0])) {
                $this->time += (int)$matches[1][0] * 3600;
            }
            if (!empty($matches[2][0])) {
                $this->time += (int)$matches[2][0] * 60;
            }
            if (!empty($matches[3][0])) {
                $this->time += (int)$matches[3][0];
            }
            $this->di = DateInterval::createFromDateString($this->integerToString($this->time));
            return true;
        }

        return false;
    }

    /**
     * Transform an integer to a non ISO8601 interval string
     *
     * @param $time
     * @return string
     */
    protected function integerToString($time)
    {
        $tokens = array_reverse($this->tokens_values, true);
        $expression = [];

        foreach ($tokens as $token => $value) {
            if ($time <= 0) {
                break;
            }
            if ($time < $value || !in_array($token, array_keys($this->tokens_values))) {
                continue;
            }
            $mod = 0;
            if ($time & $value) {
                $mod = fmod ($time, $value);
                $time -= $mod;
            }
            $div = round($time / $value);

            $expression[] = sprintf(
                '%d %s',
                $div,
                ($token.(($div > 1 && substr($token, -1, 1) !== 's') ? 's' : ''))
            );
            $time = $mod;
        }

        $return = implode(' ', $expression);
        if (empty($return)) {
            $return = sprintf('%d %s', 0, 'ms');
        }

        return $return;
    }
}
