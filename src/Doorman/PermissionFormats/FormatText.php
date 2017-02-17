<?php

namespace Peak\Doorman\PermissionFormats;

/**
 * Permission in text format
 */
class FormatText
{
    public static $values = [
        'No Permission'          => 0,
        'Execute'                => 1,
        'Write'                  => 2,
        'Write + Execute'        => 3,
        'Read'                   => 4,
        'Read + Execute'         => 5,
        'Read + Write'           => 6,
        'Read + Write + Execute' => 7
    ];
}
