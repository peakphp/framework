<?php

namespace Peak\Bedrock\View\Helper;

use Peak\Common\TextUtils;
/**
 * Misc useful view helper functions for text
 */
class Text
{
    /**
     * Wrap TextUtils into a view helper
     *
     * @param $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args = [])
    {
        return call_user_func_array([TextUtils::class, $method], $args);
    }
}
