<?php

namespace Peak\Rbac;

use Peak\Rbac\AbstractRolesHolder;

class Permission extends AbstractRolesHolder
{
    /**
     * Permission description
     * @var string
     */
    protected $desc;

    /**
     * Roles that allow this permission
     * @var array
     */
    protected $roles = [];

    /**
     * Overload parent constructor
     *
     * @param string $id   Permission identifier name
     * @param string $desc Permission optionnal description
     */
    public function __construct($id, $desc = '')
    {
        $this->id = $id;
        $this->desc = $desc;
    }

    /**
     * Get permission description
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }
}
