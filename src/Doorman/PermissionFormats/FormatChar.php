<?php

namespace Peak\Doorman\PermissionFormats;

/**
 * Permission in char format (one right only)
 */
class FormatChar
{
    public static $values = [
        'x'   => 1,
        'w'   => 2,
        'wx'  => 3,
        'r'   => 4,
        'rx'  => 5,
        'rw'  => 6,
        'rwx' => 7
    ];
}
