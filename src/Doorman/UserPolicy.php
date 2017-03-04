<?php

namespace Peak\Doorman;

use Peak\Doorman\PolicyInterface;
use Peak\Doorman\PolicySubjectInterface;

class UserPolicy implements PolicyInterface
{
    /**
     * Default user group(s)
     * @var array
     */
    protected $default_groups = [];

    /**
     * Constructor
     *
     * @param array|null $default_groups
     */
    public function __construct($default_groups = null)
    {
        if (isset($default_groups)) {
            $this->default_groups = $default_groups;
        }
    }

    /**
     * Create a user
     *
     * @param PolicySubjectInterface $subject
     */
    public function create(PolicySubjectInterface $subject)
    {
        if (!empty($this->default_groups)) {
            foreach ($this->default_groups as $group) {
                $subject->addToGroup($group);
            }
        }
    }
}
