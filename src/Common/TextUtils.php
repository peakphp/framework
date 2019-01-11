<?php

declare(strict_types=1);

namespace Peak\Common;

class TextUtils
{
    /**
     * Count the number of words in a text
     * @param string $text
     * @return int
     */
    public static function countWords(string $text): int
    {
        // split text by ' ',\r,\n,\f,\t
        $split_array = preg_split('/\s+/', $text);
        // count matches that contain alphanumerics
        $word_count = preg_grep('/[a-zA-Z0-9\\x80-\\xff]/', $split_array);

        return count($word_count);
    }

    /**
     * Count the number of paragraphs in a text
     * @param  string $text
     * @return integer
     */
    public static function countParagraphs(string $text): int
    {
        // count \r or \n characters
        return count(preg_split('/[\r\n]+/', $text));
    }

    /**
     * Count the number of characters in a text
     * @param string $text
     * @param bool $includeSpaces
     * @return int
     */
    public static function countChars(string $text, bool $includeSpaces = false): int
    {
        if ($includeSpaces === false) {
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
     * @param  bool    $breakWords
     * @param  bool    $middle
     * @return mixed   return a string if success, or false if substr() fail
     */
    public static function truncate(
        string $string,
        int $length = 80,
        string $etc = '...',
        bool $breakWords = false,
        bool $middle = false
    ) {
        if ($length == 0) {
            return '';
        }

        if (strlen($string) > $length) {
            $length -= min($length, strlen($etc));
            if (!$breakWords && !$middle) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
            }
            if (!$middle) {
                return substr($string, 0, $length) . $etc;
            } else {
                $half_length = (int)round($length/2, 0);
                return substr($string, 0, $half_length) . $etc . substr($string, -$half_length);
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
    public static function wordwrap(string $string, int $length = 80, string $break = "\n", bool $cut = false): string
    {
        return wordwrap($string, $length, $break, $cut);
    }
}
