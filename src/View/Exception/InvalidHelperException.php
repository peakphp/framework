<?php

declare(strict_types=1);

namespace Peak\View\Exception;

class InvalidHelperException extends \Exception
{
    /**
     * @var mixed
     */
    private $helper;

    /**
     * InvalidHelperException constructor.
     * @param mixed $helper
     */
    public function __construct($helper)
    {
        parent::__construct('invalid view helper');
        $this->helper = $helper;
    }

    /**
     * @return mixed
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
