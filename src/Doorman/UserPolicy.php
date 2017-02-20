<?php

namespace Peak\Doorman;

use Peak\Doorman\PolicyInterface;

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
        if(isset($default_groups)) {
            $this->default_groups = $default_groups;
        }
    }

    /**
     * Create user
     * 
     * @param User $user      
     */
    public function create(User $user)
    {
        if(!empty($this->default_groups)) {
            foreach($this->default_groups as $group) {
                $user->addToGroup($group);
            }
        }
    }
}
