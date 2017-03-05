<?php

namespace Peak;

/**
 * Manage a global timer and/or multiple timers
 */
class Chrono
{
    /**
     * Global timer, used by default if not timer name is specified
     * @var array
     */
    private static $global = [
        'start' => false,
        'end' => false
    ];

    /**
     * Timers list
     * @var array
     */
    private static $timers = [];

    /**
     * Start global timer of a specific timer name if set
     *
     * @param string|null timer name
     */
    public static function start($timer_name = null)
    {
        if (!isset($timer_name)) {
            self::$global = [
                'start' => self::getMicrotime(),
                'end' => false
            ];
        }
        else self::$timers[$timer_name] = [
            'start' => self::getMicrotime(),
            'end' => false
        ];
    }

    /**
     * Stop global timer or a specific timer name if set
     *
     * @param string|null timer name
     */
    public static function stop($timer_name = null)
    {       
        if (self::timerExists($timer_name)) {
            self::$timers[$timer_name]['end'] = self::getMicrotime();
        } else {
            self::$global['end'] = self::getMicrotime();
        }
    }

    /**
     * Check if a timer name exists
     *
     * @param  string $name
     * @return bool
     */
    public static function timerExists($name)
    {
        return array_key_exists($name, self::$timers);
    }

    /**
     * Check if chrono is started but not ended
     *
     * @return bool
     */
    public static function isOn($timer_name = null)
    {
        if (!isset($timer_name)) {
            if ((self::$global['start'] === false) || (self::$global['end'] !== false)) {
                return false;
            }
            return true;
        }
        elseif (self::timerExists($timer_name)) {
            if ((self::$timers[$timer_name]['start'] === false) || (self::$timers[$timer_name]['end'] !== false)) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Check if a timer is completed (started and ended)
     *
     * @param  string|null $timer_name
     * @return bool
     */
    public static function isCompleted($timer_name = null)
    {
        if (!isset($timer_name)) {
            if ((self::$global['start'] !== false) && (self::$global['end'] !== false)) {
                return true;
            }
            return false;
        }
        elseif (self::timerExists($timer_name)) {
            if ((self::$timers[$timer_name]['start'] !== false) && (self::$timers[$timer_name]['end'] !== false)) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Get current microtime
     *
     * @return integer
     */
    public static function getMicrotime()
    {
        return microtime(true);
    }

    /**
     * Stop chrono if not ended and return the time elapsed in seconds
     *
     * @param  integer       $decimal_precision
     * @param  string|null   $timer_name
     * @return integer|false return false if timer do not exists or it is not valid
     */
    public static function get($decimal_precision = 2, $timer_name = null)
    {
        if (self::isOn($timer_name)) self::stop($timer_name);

        if (self::isCompleted($timer_name)) {
            if (self::timerExists($timer_name)) {
                $time_elapsed = self::_elapsed(self::$timers[$timer_name]);
            } else {
                $time_elapsed = self::_elapsed(self::$global);
            }
            return round(($time_elapsed), $decimal_precision);
        }

        return false;
    }

    /**
     * Same as get() but return timer in milliseconds
     *
     * @see get()
     */
    public static function getMS($decimal_precision = 4, $timer_name = null)
    {
        $sec = self::get($decimal_precision, $timer_name);
        if ($sec === false) return false;
        
        return $sec * 1000;      
    }

    /**
     * Reset global timer or a specific timer
     *
     * @param string|null $timer_name
     */
    public static function reset($timer_name = null)
    {
        if (isset($timer_name)) {
            if (self::timerExists($timer_name)) unset(self::$timers[$timer_name]);
        }
        else {
            self::$global = ['start' => false, 'end' => false];
        }
    }

    /**
     * Reset all timers
     */
    public static function resetAll()
    {
        self::reset();
        self::$timers = [];
    }

    /**
     * Calculate the difference between microtime(s) values
     *
     * @param  array $timer_array
     * @return integer
     */
    private static function _elapsed($timer_array)
    {
        return ($timer_array['end'] - $timer_array['start']);
    }
}
