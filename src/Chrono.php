<?php
/** 
 * Manage a global timer and/or multiple timers
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_Chrono
{

    /**
     * Global timer, used by default if not timer name is specified
     * @var array
     */
    private static $_global = array('start' => false, 'end' => false);

    /**
     * Timers list
     * @var array
     */
    private static $_timers = array();

    /**
     * Start global timer of a specific timer name if set
     * 
     * @param string|null timer name
     */
    public static function start($timer_name = null)
    {
        if(!isset($timer_name)) {
            self::$_global = array('start' => self::getMicrotime(), 'end' => false);      
        }
        else self::$_timers[$timer_name] = array('start' => self::getMicrotime(), 'end' => false);
    }

    /**
     * Stop global timer or a specific timer name if set
     * 
     * @param string|null timer name
     */
    public static function stop($timer_name = null)
    {       
        if(self::timerExists($timer_name)) {
            self::$_timers[$timer_name]['end'] = self::getMicrotime();
        }
        else self::$_global['end'] = self::getMicrotime();     
    }

    /**
     * Check if a timer name exists
     *
     * @param  string $name
     * @return bool
     */
    public static function timerExists($name)
    {
        return array_key_exists($name, self::$_timers);
    }

    /**
     * Check if chrono is started but not ended
     *
     * @return bool
     */
    public static function isOn($timer_name = null)
    {
        if(!isset($timer_name)) {
            if((self::$_global['start'] === false) || (self::$_global['end'] !== false)) return false;
            else return true;
        }
        else {
            if(self::timerExists($timer_name)) {
                if((self::$_timers[$timer_name]['start'] === false) || (self::$_timers[$timer_name]['end'] !== false)) return false;
                else return true;
            }
            return false;
        }   
    }

    /**
     * Check if a timer is completed (started and ended)
     *
     * @param  string|null $timer_name
     * @return bool
     */
    public static function isCompleted($timer_name = null)
    {
        if(!isset($timer_name)) {
            if((self::$_global['start'] !== false) && (self::$_global['end'] !== false)) return true;
            else return false;
        }
        else {
           if(self::timerExists($timer_name)) {
               if((self::$_timers[$timer_name]['start'] !== false) && (self::$_timers[$timer_name]['end'] !== false)) return true;
               else return false;
           }
           else return false;
        } 
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
     * @param  string|null   $timer_name
     * @param  integer       $decimal_precision
     * @return integer|false return false if timer do not exists or it is not valid
     */
    public static function get($timer_name = null, $decimal_precision = 2)
    {
        if(self::isOn($timer_name)) self::stop($timer_name);

        if(self::isCompleted($timer_name)) {
            if(self::timerExists($timer_name)) {
                $time_elapsed = self::_elapsed(self::$_timers[$timer_name]);
            }
            else {
                $time_elapsed = self::_elapsed(self::$_global);
            }
            return round(($time_elapsed), $decimal_precision);
        }        
        else return false;
    }

    /**
     * Same as get() but return timer in milliseconds
     *
     * @see get()
     */
    public static function getMS($timer_name = null, $decimal_precision = 4)
    {
        $sec = self::get($timer_name, $decimal_precision);
        if($sec === false) return false;
        
        return $sec * 1000;      
    }

    /**
     * Reset global timer or a specific timer
     *
     * @param string|null $timer_name
     */
    public static function reset($timer_name = null)
    {
        if(isset($timer_name)) {
            if(self::timerExists($timer_name)) unset(self::$_timers[$timer_name]);
        }
        else self::$_global = array('start' => false, 'end' => false);  
    }

    /**
     * Reset all timers
     */
    public static function resetAll()
    {
        self::reset();
        self::$_timers = array();
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