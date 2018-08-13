<?php

declare(strict_types=1);

namespace Peak\Climber\Cron;

/**
 * Class OptionFormat
 * @package Peak\Climber\Cron
 */
class OptionFormat
{
    /**
     * YesNo  option formats
     * @var array
     */
    public static $yesno = [
        'y' => 1,
        'yes' => 1,
        '1' => 1,
        'true' => 1,
        true => 1,
        'n' => 0,
        'no' => 0,
        'false' => 0,
        '0' => 0,
        false => 0,
    ];

    /**
     * Repeat option formats
     * @var array
     */
    public static $repeat = [
        'never' => -1,
        'no' => -1,
        'n' => -1,
        '-1' => -1,
        false => -1,
        'yes' => 0,
        'y' => 0,
        'always' => 0,
        '*' => 0,
        true => 0,
        0 => 0,
        -1 => -1,
    ];

    /**
     * Handle Yes/No value
     *
     * @param $option
     * @return mixed|null
     */
    public static function yesNo($option)
    {
        $result = null;
        $option = strtolower($option);
        if (array_key_exists($option, self::$yesno)) {
            $result = self::$yesno[$option];
        }
        return $result;
    }

    /**
     * Handle Yes/No value
     *
     * @param $option
     * @return mixed|null
     */
    public static function yesNoValid($option)
    {
        if (self::yesNo($option) === null) {
            return false;
        }
        return true;
    }

    /**
     * Handle Repeat value
     *
     * @param $option
     * @return mixed|null
     */
    public static function repeat($option)
    {
        $result = null;
        $option = strtolower($option);
        if (array_key_exists($option, self::$repeat)) {
            $result = self::$repeat[$option];
        } elseif (is_numeric($option)) {
            $result = $option;
        }
        return $result;
    }

    /**
     * Check if repeat format is valid
     *
     * @param $option
     * @return bool
     */
    public static function repeatValid($option)
    {
        if (self::repeat($option) === null) {
            return false;
        }
        return true;
    }
}
