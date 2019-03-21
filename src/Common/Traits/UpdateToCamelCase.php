<?php

namespace Peak\Common\Traits;

use function lcfirst;
use function str_replace;
use function ucwords;

trait UpdateToCamelCase
{
    /**
     * @param array $data
     * @return array
     */
    protected function updateArrayToCamelCase(array $data): array
    {
        $newData = [];
        foreach ($data as $k => $v) {
            $k = $this->updateStringToCamelCase($k);
            $newData[$k] = $v;
        }
        return $newData;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function updateStringToCamelCase(string $text): string
    {
        return lcfirst(str_replace(['_', '-', ' '],'', lcfirst(ucwords(strtolower($text), '_- '))));
    }
}
