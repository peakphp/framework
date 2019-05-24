<?php

declare(strict_types=1);

namespace Peak\View\Exception;

class VarNotFoundException extends \Exception
{
    /**
     * VarNotFoundException constructor.
     * @param string $var
     */
    public function __construct(string $var)
    {
        parent::__construct('view variable ['.$var.'] not found');
    }
}
