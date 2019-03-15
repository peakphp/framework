<?php

declare(strict_types=1);

namespace Peak\Common;

use function ini_set;
use function is_array;
use function print_r;

class PhpIni
{
    /**
     * PhpIni constructor.
     * @param array $definitions
     * @param bool $strict
     * @throws \Exception
     */
    public function __construct(array $definitions, bool $strict = false)
    {
        if (empty($definitions)) {
            return;
        }

        foreach ($definitions as $setting => $val) {
            if (!is_array($val)) {
                $this->set($setting, $val, $strict);
            } else {
                foreach ($val as $k => $v) {
                    $this->set($setting, $val, $strict);
                }
            }
        }
    }

    /**
     * @param string $option
     * @param mixed $value
     * @param bool $strict
     * @throws \Exception
     */
    private function set(string $option, $value, bool $strict = false)
    {
        $result = ini_set($option, (string)$value);
        if ($strict && $result === false) {
            throw new \Exception('Fail to set php option '.$option.' to "'.print_r($value, true).'"');
        }
    }
}
