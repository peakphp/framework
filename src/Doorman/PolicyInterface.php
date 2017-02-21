<?php

namespace Peak\Doorman;

use Peak\Doorman\PolicySubjectInterface;

interface PolicyInterface
{
    public function create(PolicySubjectInterface $subject);
}
