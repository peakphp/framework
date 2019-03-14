<?php

declare(strict_types=1);

namespace Peak\Bedrock\Bootstrap\Exception;

class InvalidBootableProcessException extends \Exception
{
    /**
     * @var mixed
     */
    private $process;

    /**
     * InvalidBootableProcessException constructor.
     *
     * @param mixed $process
     */
    public function __construct($process)
    {
        parent::__construct('Invalid bootable process');
        $this->process = $process;
    }

    /**
     * @return mixed
     */
    public function getProcess()
    {
        return $this->process;
    }
}
