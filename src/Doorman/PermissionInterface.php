<?php

namespace Peak\Doorman;

interface PermissionInterface
{
    public function get();

    public function set($perm);
}
