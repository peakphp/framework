<?php

namespace Peak\Doorman\PermissionFormats;

/**
 * Permission in binary format
 */
class FormatBinary
{
    public static $values = [
        '000' => 0,
        '001' => 1,
        '010' => 2,
        '011' => 3,
        '100' => 4,
        '101' => 5,
        '110' => 6,
        '111' => 7
    ];
}
