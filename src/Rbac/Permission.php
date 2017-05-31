<?php

namespace Peak\Rbac;

use Peak\Rbac\AbstractHolder;
use Peak\Rbac\RolesHolder;

class Permission extends AbstractHolder
{
    use RolesHolder;
    
    /**
     * Permission description
     * @var string
     */
    protected $desc;

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
