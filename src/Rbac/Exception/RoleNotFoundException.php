<?php

declare(strict_types=1);

namespace Peak\Rbac\Exception;

/**
 * Class RoleNotFoundException
 * @package Peak\Rbac\Exception
 */
class RoleNotFoundException extends \Exception
{
    /**
     * RoleNotFoundException constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct('Role ['.$id.'] not found');
    }
}
