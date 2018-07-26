<?php

declare(strict_types=1);

namespace Peak\Rbac;

/**
 * Class CustomPermission
 * @package Peak\Rbac
 */
class CustomPermission
{
    /**
     * Allow flag
     */
    const ALLOW = true;

    /**
     * Deny flag
     */
    const DENY = false;

    /**
     * Permission instance
     * @var Permission
     */
    protected $permission;

    /**
     * Permission allowed status
     * @var bool
     */
    protected $allowed;

    /**
     * Constructor
     *
     * @param Permission $perm
     * @param boolean    $allowed
     */
    public function __construct(Permission $perm, bool $allowed)
    {
        $this->permission = $perm;
        $this->allowed = $allowed;
    }

    /**
     * Access to permission object
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call(string $method, $args = null)
    {
        return call_user_func_array([$this->permission, $method], $args);
    }

    /**
     * Is permission allowed
     *
     * @return boolean
     */
    public function isAllowed(): bool
    {
        return $this->allowed;
    }
}
