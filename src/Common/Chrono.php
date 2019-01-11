<?php

namespace Peak\Common;

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
     * @param string|null $timerName
     */
    public static function start(string $timerName = null)
    {
        if (!isset($timerName)) {
            self::$global = [
                'start' => self::getMicrotime(),
                'end' => false
            ];
        } else {
            self::$timers[$timerName] = [
                'start' => self::getMicrotime(),
                'end' => false
            ];
        }
    }

    /**
     * Stop global timer or a specific timer name if set
     * @param string|null $timerName
     */
    public static function stop(string $timerName = null)
    {
        if (self::timerExists($timerName)) {
            self::$timers[$timerName]['end'] = self::getMicrotime();
        } else {
            self::$global['end'] = self::getMicrotime();
        }
    }

    /**
     * Check if a timer name exists
     * @param string|null $timerName
     * @return bool
     */
    public static function timerExists(?string $timerName)
    {
        return array_key_exists($timerName, self::$timers);
    }

    /**
     * Check if chrono is started but not ended
     * @param string|null $timerName
     * @return bool
     */
    public static function isOn(string $timerName = null): bool
    {
        if (!isset($timerName)) {
            if ((self::$global['start'] === false) || (self::$global['end'] !== false)) {
                return false;
            }
            return true;
        } elseif (self::timerExists($timerName)) {
            if ((self::$timers[$timerName]['start'] === false) || (self::$timers[$timerName]['end'] !== false)) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Check if a timer is completed (started and ended)
     * @param null $timerName
     * @return bool
     */
    public static function isCompleted($timerName = null): bool
    {
        if (!isset($timerName)) {
            if ((self::$global['start'] !== false) && (self::$global['end'] !== false)) {
                return true;
            }
            return false;
        } elseif (self::timerExists($timerName)) {
            if ((self::$timers[$timerName]['start'] !== false) && (self::$timers[$timerName]['end'] !== false)) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Get current microtime
     * @return mixed
     */
    public static function getMicrotime()
    {
        return microtime(true);
    }

    /**
     * Stop chrono if not ended and return the time elapsed in seconds
     * @param  integer       $decimalPrecision
     * @param  string|null   $timerName
     * @return bool|float    return false if timer do not exists or it is not valid
     */
    public static function get(int $decimalPrecision = 2, string $timerName = null)
    {
        if (self::isOn($timerName)) {
            self::stop($timerName);
        }

        if (self::isCompleted($timerName)) {
            if (self::timerExists($timerName)) {
                $time_elapsed = self::elapsed(self::$timers[$timerName]);
            } else {
                $time_elapsed = self::elapsed(self::$global);
            }
            return round(($time_elapsed), $decimalPrecision);
        }

        return false;
    }

    /**
     * Same as get() but return timer in milliseconds
     * @param int $decimalPrecision
     * @param null $timerName
     * @return bool|float|int
     */
    public static function getMS(int $decimalPrecision = 4, $timerName = null)
    {
        $sec = self::get($decimalPrecision, $timerName);
        if ($sec === false) {
            return false;
        }
        
        return $sec * 1000;
    }

    /**
     * Reset global timer or a specific timer
     * @param null $timerName
     */
    public static function reset($timerName = null)
    {
        if (isset($timerName)) {
            if (self::timerExists($timerName)) {
                unset(self::$timers[$timerName]);
            }
        } else {
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
     * @param  array $timerArray
     * @return integer
     */
    private static function elapsed(array $timerArray)
    {
        return ($timerArray['end'] - $timerArray['start']);
    }
}
