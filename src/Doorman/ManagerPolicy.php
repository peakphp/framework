<?php

namespace Peak\Doorman;

use Peak\Doorman\PolicyInterface;
use Peak\Doorman\PolicySubjectInterface;

class ManagerPolicy implements PolicyInterface
{
    /**
     * Create root default account
     *
     * @param PolicySubjectInterface $subject
     */
    public function create(PolicySubjectInterface $subject)
    {
        $subject->addUser(new SuperUser());
        $subject->addGroup(new SuperGroup());
        $subject->user('root')->addToGroup($subject->group('root'));
    }
}
