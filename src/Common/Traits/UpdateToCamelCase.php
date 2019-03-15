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
    private function updateArrayToCamelCase(array $data): array
    {
        $newData = [];
        foreach ($data as $k => $v) {
            $k = $this->updateToCamelCase($k);
            $newData[$k] = $v;
        }
        return $newData;
    }

    /**
     * @param string $string
     * @return string
     */
    private function updateToCamelCase(string $text): string
    {
        return str_replace('_','', lcfirst(ucwords($text, '_')));
    }
}
