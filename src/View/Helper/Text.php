<?php

namespace Peak\View\Helper;

use Peak\View\Helper;

/**
 * Misc usefull view helper functions for text
 */
class Text extends Helper
{
    
    
    /**
     * Count the number of words in a text
     *
     * @param string $text
     * @return integer
     */
    public function countWords($text)
    {
        // split text by ' ',\r,\n,\f,\t
        $split_array = preg_split('/\s+/',$text);
        // count matches that contain alphanumerics
        $word_count = preg_grep('/[a-zA-Z0-9\\x80-\\xff]/', $split_array);

        return count($word_count);
    }
    
    /**
     * Count the number of paragraphs in a text
     *
     * @param string $text
     * @return integer
     */
    public function countParagraphs($text)
    {
        // count \r or \n characters
        return count(preg_split('/[\r\n]+/', $text));
    }
    
    /**
     * Count the number of characters in a text
     *
     * @param string  $text
     * @param boolean $include_spaces
     * @return string
     */
    public function countChars($text, $include_spaces = false)
    {
        if ($include_spaces) return(strlen($text));

        return preg_match_all("/[^\s]/",$string, $match);
    }
    
    /**
     * Truncate a string to a certain length if necessary, optionally splitting in the
     * middle of a word, and appending the $etc string or inserting $etc into the middle.
     *
     * @param string $string
     * @param integer $length
     * @param string $etc
     * @param bool $break_words
     * @param bool $middle
     * @return string
     */
    public function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
    {
        if ($length == 0) return '';

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
        else return $string;
    }
    
    /**
     * Wrap a string of text at a given length
     *
     * @param string  $string
     * @param integer $length
     * @param string  $break
     * @param bool    $cut
     * @return string
     */
    public function wordwrap($string, $length = 80, $break = "\n", $cut = false)
    {
        return wordwrap($string,$length,$break,$cut);
    }
}
