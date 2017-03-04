<?php

namespace Peak\Doorman;

use Peak\Doorman\Ability;
use Peak\Doorman\User;
use Peak\Doorman\SuperUser;

class AbilityResolver
{
    /**
     * User
     * @var Peak\Doorman\User
     */
    protected $user;

    /**
     * Ability
     * @var Peak\Doorman\Ability
     */
    protected $ability;

    /**
     * Constructor
     *
     * @param User    $user   
     * @param Ability $ability
     */
    public function __construct(User $user, Ability $ability)
    {
        $this->user = $user;
        $this->ability = $ability;
    }

    /**
     * Check user permission over the current ability
     *
     * @param  integer $permissions
     * @return boolean
     */
    public function can($perm)
    {
        return $this->_can($perm);
    }

    /**
     * Get current ability for the user
     * @return int
     */
    public function abilityPermission()
    {
        // check for custom user ability overrride
        $perms = $this->user->getCustomAbility(
            $this->ability->getName()
        );

        // if no custom user ability, use default ability perms
        if ($perms === null) {
            $perms = $this->ability->permissions;
        }
        else {
            $perms = $perms->permissions;
        }

        // resolver user, group and other in this order
        if ($this->user->is($this->ability->owner->getName())) {
            $iperm = $perms->getUserPerm();
        }
        elseif ($this->user->isInGroup($this->ability->group->getName())) {
            $iperm = $perms->getGroupPerm();
        }
        else $iperm = $perms->getOthersPerm();

        return $iperm;
    }

    /**
     * Internal method of can()
     *
     * @param  integer $permission
     * @return boolean
     */
    protected function _can($perm)
    {
        $perm = new Permission($perm);

        // bypass
        if ($this->user instanceof SuperUser) {
            return true;
        }

        $ability_perm = $this->abilityPermission();

        return ($ability_perm >= $perm->get());
    }
}
