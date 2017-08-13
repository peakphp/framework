<?php

namespace Peak\Common;

class TextUtils
{
    /**
     * Count the number of words in a text
     *
     * @param  string $text
     * @return integer
     */
    public static function countWords($text)
    {
        // split text by ' ',\r,\n,\f,\t
        $split_array = preg_split('/\s+/', $text);
        // count matches that contain alphanumerics
        $word_count = preg_grep('/[a-zA-Z0-9\\x80-\\xff]/', $split_array);

        return count($word_count);
    }

    /**
     * Count the number of paragraphs in a text
     *
     * @param  string $text
     * @return integer
     */
    public static function countParagraphs($text)
    {
        // count \r or \n characters
        return count(preg_split('/[\r\n]+/', $text));
    }

    /**
     * Count the number of characters in a text
     *
     * @param  string  $text
     * @param  boolean $include_spaces
     * @return string
     */
    public static function countChars($text, $include_spaces = false)
    {
        if ($include_spaces === false) {
            $text = preg_replace("/[\s]/", '', $text);
        }
        return mb_strlen($text);
    }

    /**
     * Truncate a string to a certain length if necessary, optionally splitting in the
     * middle of a word, and appending the $etc string or inserting $etc into the middle.
     *
     * @param  string  $string
     * @param  integer $length
     * @param  string  $etc
     * @param  bool    $break_words
     * @param  bool    $middle
     * @return string
     */
    public static function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
    {
        if ($length == 0) {
            return '';
        }

        if (strlen($string) > $length) {
            $length -= min($length, strlen($etc));
            if (!$break_words && !$middle) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
            }
            if (!$middle) {
                return substr($string, 0, $length) . $etc;
            } else {
                return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
            }
        }

        return $string;
    }

    /**
     * Wrap a string of text at a given length
     *
     * @param  string  $string
     * @param  integer $length
     * @param  string  $break
     * @param  bool    $cut
     * @return string
     */
    public static function wordwrap($string, $length = 80, $break = "\n", $cut = false)
    {
        return wordwrap($string, $length, $break, $cut);
    }
}
