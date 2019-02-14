<?php

use \Peak\Bedrock\AbstractBootstrapper;

class Bootstrapper extends AbstractBootstrapper
{

    public $i = 0;
    public $j = 0;

    protected $processes = [
        BootstrapProcess::class
    ];

    public function envDev()
    {
        $this->j++;
    }

    public function envProd()
    {
        $this->j++;
    }

    public function initRandom()
    {
        $this->i++;
    }

    public function notInitRandom()
    {
        $this->i++;
    }

}