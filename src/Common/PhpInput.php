<?php

namespace Peak\Common;

/**
 * Wrapper around php://input
 */
class PhpInput extends Collection
{
    /**
     * PhpInput constructor.
     */
    public function __construct()
    {
        $raw  = file_get_contents('php://input');
        $post = json_decode($raw, true); // for json input

        // in case json post is empty but $_POST is not we will use it
        if (!empty($raw) && empty($post) && filter_input_array(INPUT_POST)) {
            $post = filter_input_array(INPUT_POST);
        }

        parent::__construct($post);
    }
}
