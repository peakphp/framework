<?php

use \Peak\Blueprint\Common\Bootable;

class BootstrapProcess implements Bootable
{
    public function boot()
    {
        $_GET[__CLASS__] = __CLASS__;
    }
}