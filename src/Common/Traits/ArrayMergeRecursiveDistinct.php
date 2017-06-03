<?php

namespace Peak\Common\Traits;

trait ArrayMergeRecursiveDistinct
{
    /**
     * Merge 2 arrays recursively overwriting the keys in the first array if such key already exists
     *
     * @param  array $a
     * @param  array $b
     * @return array
     */
    protected function arrayMergeRecursiveDistinct($a, $b)
    {
        // merge arrays if both variables are arrays
        if (is_array($a) && is_array($b)) {
            // loop through each right array's entry and merge it into $a
            foreach ($b as $key => $value) {
                if (isset($a[$key])) {
                    $a[$key] = $this->arrayMergeRecursiveDistinct($a[$key], $value);
                } else {
                    if ($key === 0) {
                        $a = [0 => $this->arrayMergeRecursiveDistinct($a, $value)];
                    } else {
                        $a[$key] = $value;
                    }
                }
            }
            return $a;
        }

        return $b;
    }
}
