<?php

declare(strict_types=1);

namespace Peak\Common\Traits;

trait ArrayMergeRecursiveDistinct
{
    /**
     * Merge 2 arrays recursively overwriting the keys in the first array if such key already exists
     *
     * @param array $a
     * @param array $b
     * @param bool $preserveKeys add instead overwritten when index key is numeric
     * @return array
     */
    protected function arrayMergeRecursiveDistinct(array $a, array $b, $mergeNumericKeys = false): array
    {
        $merged = $a;

        foreach($b as $key => $value) {
            if (!$mergeNumericKeys && is_numeric($key)) {
                $merged[] = $value;
                continue;
            }
            if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
