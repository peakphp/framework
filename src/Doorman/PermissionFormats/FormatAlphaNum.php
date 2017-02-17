<?php

namespace Peak\Doorman\PermissionFormats;

/**
 * Permission in alphanumeric format
 */
class FormatAlphaNum
{
    public static $values = [
        '---' => 0,
        '--x' => 1,
        '-w-' => 2,
        '-wx' => 3,
        'r--' => 4,
        'r-x' => 5,
        'rw-' => 6,
        'rwx' => 7
    ];
}
