<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application\Exceptions;

/**
 * Class PathNotFoundException
 * @package Peak\Bedrock\Application\Exceptions
 */
class PathNotFoundException extends \Exception
{
    /**
     * PathNotFoundException constructor.
     * @param $path
     * @param null $context
     */
    public function __construct($path, $context = null)
    {
        $str = 'Path '.trim(strip_tags($path)).' not found!';
        if (isset($context)) {
            $str .= ' Context: '.$context;
        }
        parent::__construct($str);
    }
}
