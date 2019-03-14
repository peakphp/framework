<?php

declare(strict_types=1);

namespace Peak\Blueprint\Common;

interface Validator
{
    /**
     * Valid a mixed $value
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool;
}
