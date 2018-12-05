<?php

declare(strict_types=1);

namespace Peak\View\Exception;

/**
 * Class InvalidMacroException
 * @package Peak\View\Exception
 */
class InvalidMacroException extends \Exception
{
    /**
     * @var mixed
     */
    private $macro;

    /**
     * InvalidMacroException constructor.
     * @param $macro
     */
    public function __construct($macro)
    {
        parent::__construct('Invalid view macro closure');
        $this->macro = $macro;
    }

    /**
     * @return mixed
     */
    public function getMacro()
    {
        return $this->macro;
    }
}
