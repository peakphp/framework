<?php

namespace Peak\Doorman;

use Peak\Doorman\Ability;
use Peak\Doorman\Permissions;

/**
 * User ability permissions override
 */
class CustomUserAbility
{
    /**
     * Ability object
     * @var \Peak\Doorman\Ability
     */
    public $ability;

    /**
     * Permissions object
     * @var \Peak\Doorman\Permissions
     */
    public $permissions;

    /**
     * Associated a ability with a custom permissions object
     *
     * @param Ability     $ability
     * @param Permissions $perm
     */
    public function __construct(Ability $ability, Permissions $perm)
    {
        $this->ability = $ability;
        $this->permissions = $perm;
    }

    /**
     * Get ability name (Shortcut)
     *
     * @return string
     */
    public function getName()
    {
        return $this->ability->getName();
    }
}
