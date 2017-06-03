<?php

namespace Peak\Doorman;

use Peak\Common\Collection;
use Peak\Doorman\Ability;
use Peak\Doorman\AbilityResolver;
use Peak\Doorman\Group;
use Peak\Doorman\Permission;
use Peak\Doorman\PolicySubjectInterface;

/**
 * User entity
 */
class User implements PolicySubjectInterface
{
    /**
     * User name or unique id
     * @var string|integer
     */
    protected $name;

    /**
     * User group(s)
     * @var Collection
     */
    protected $groups;

    /**
     * User custom abilities
     * @var Collection
     */
    protected $custom_abilities;

    /**
     * Create the user
     *
     * @param string|integer $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->groups = new Collection();
        $this->custom_abilities = new Collection();
    }

    /**
     * Get current user name of unique id
     *
     * @return string|integer
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add a user to group(s)
     *
     * @param  Group $groups
     * @return $this
     */
    public function addToGroup(...$groups)
    {
        foreach ($groups as $group) {
            if (is_array($group)) {
                foreach ($group as $g) {
                    $this->groups[$g->getName()] = $g;
                }
                return $this;
            }
            $this->groups[$group->getName()] = $group;
        }
        return $this;
    }

    /**
     * Remove user from a group
     *
     * @param  Group  $group
     * @return $this
     */
    public function removeFromGroup(Group $group)
    {
        unset($this->groups[$group->getName()]);
        return $this;
    }

    /**
     * Remove user from all groups
     */
    public function removeFromAllGroups()
    {
        $this->groups->strip();
    }

    /**
     * Add custom ability
     *
     * @param   CustomUserAbility $custom
     * @return  $this
     */
    public function addCustomAbility(CustomUserAbility $custom)
    {
        $this->custom_abilities[$custom->getName()] = $custom;
        return $this;
    }

    /**
     * Get a custom ability if exists
     *
     * @param  string $name
     * @return CustomUserAbility|null
     */
    public function getCustomAbility($name)
    {
        if (isset($this->custom_abilities->$name)) {
            return $this->custom_abilities->$name;
        }
        return null;
    }

    /**
     * Check if user match name or id
     *
     * @param  string|integer  $name
     * @return boolean
     */
    public function is($name)
    {
        return ($this->name === $name);
    }

    /**
     * Check if the current user is in a group name
     *
     * @param  string  $name
     * @return boolean
     */
    public function isInGroup($name)
    {
        return isset($this->groups->$name);
    }

    /**
     * Check user permission for an ability
     *
     * @param  Ability $ability
     * @param  mixed   $permission
     * @return boolean
     */
    public function can(Ability $ability, $permission)
    {
        $resolver = new AbilityResolver($this, $ability);
        return $resolver->can($permission);
    }

    /**
     * Get current user permission for an ability
     *
     * @param  Ability $ability
     * @param  mixed   $permission
     * @return boolean
     */
    public function abilityPermission(Ability $ability)
    {
        $resolver = new AbilityResolver($this, $ability);
        return $resolver->abilityPermission();
    }

    /**
     * Can read the ability
     *
     * @param  Ability $ability
     * @return boolean
     */
    public function canRead(Ability $ability)
    {
        return $this->can($ability, Permission::READ);
    }

    /**
     * Can write the ability
     *
     * @param  Ability $ability
     * @return boolean
     */
    public function canWrite(Ability $ability)
    {
        return $this->can($ability, Permission::WRITE);
    }

    /**
     * Can execute the ability
     *
     * @param  Ability $ability
     * @return boolean
     */
    public function canExecute(Ability $ability)
    {
        return $this->can($ability, Permission::EXECUTE);
    }
}
