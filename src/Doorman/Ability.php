<?php

namespace Peak\Doorman;

use Peak\Doorman\User;
use Peak\Doorman\Group;
use Peak\Doorman\Permissions;

class Ability
{
    /**
     * Permissions of ability
     * @var Peak\Doorman\Permissions
     */
    public $permissions;

    /**
     * Owner of ability (aka as user)
     * @var Peak\Doorman\User
     */
    public $owner;

    /**
     * Group of ability
     * @var Peak\Doorman\Group
     */
    public $group;
    
    /**
     * Ability name
     * @var string
     */
    protected $name;
    
    /**
     * Constructor
     *
     * @param string      $name
     * @param User        $owner
     * @param Group       $group
     * @param Permissions $permissions
     */
    public function __construct($name, User $owner, Group $group, Permissions $permissions)
    {
        $this->name        = $name;
        $this->owner       = $owner;
        $this->group       = $group;
        $this->permissions = $permissions;
    }

    /**
     * Get ability name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
