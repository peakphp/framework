<?php

namespace Peak\Doorman;

interface PolicyInterface
{
    public function create(User $user);
}
