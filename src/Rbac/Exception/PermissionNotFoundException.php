<?php

declare(strict_types=1);

namespace Peak\Rbac\Exception;

/**
 * Class PermissionNotFoundException
 * @package Peak\Rbac\Exception
 */
class PermissionNotFoundException extends \Exception
{
    /**
     * PermissionNotFoundException constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct('Permission ['.$id.'] not found');
    }
}
