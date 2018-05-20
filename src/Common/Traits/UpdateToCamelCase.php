<?php

namespace Peak\Common\Traits;

/**
 * Trait UpdateToCamelCase
 * @package Peak\Common\Traits
 */
trait UpdateToCamelCase
{
    /**
     * @param array $data
     * @return array
     */
    private function updateToCamelCase($data)
    {
        $newData = [];
        foreach ($data as $k => $v) {
            $k = str_replace('_','', lcfirst(ucwords($k, '_')));
            $newData[$k] = $v;
        }
        return $newData;
    }
}
