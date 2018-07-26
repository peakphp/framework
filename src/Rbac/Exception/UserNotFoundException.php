<?php

declare(strict_types=1);

namespace Peak\Rbac\Exception;

/**
 * Class UserNotFoundException
 * @package Peak\Rbac\Exception
 */
class UserNotFoundException extends \Exception
{
    /**
     * UserNotFoundException constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct('User ['.$id.'] not found');
    }
}
