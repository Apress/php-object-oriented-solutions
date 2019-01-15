<?php
/**
 * Helper methods.
 * 
 * Currently contains just one static method to extract a specified
 * number of sentences from the beginning of a longer piece of text.
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.0
 */
class Pos_Utils
{

    /**
     * Extracts a specified number of sentences from the beginning of text.
     * 
     * Takes two arguments: the text from which the sentences are to be
     * extracted, and the number of sentences required. The second
     * argument is optional, and defaults to 2. Setting the second 
     * argument to 0 results in the whole text being returned.
     * 
     * The method returns an array consisting of two elements: the first
     * element contains the requested sentences, the second is a Boolean
     * value indicating whether any text remains after extracting the
     * sentences. This is useful for displaying a "more" link leading to 
     * the full text.
     *
     * @param string $text  The text from which the sentences are to be extracted.
     * @param int $number   The number of sentences required; defaults to 2; 0 returns full text.
     * @return array        First element contains the extracted sentences; second element is a Boolean indicating
     *                      whether any text remains.
     */
    public static function getFirst($text, $number = 2)
    {
        $result = array();
        if ($number == 0) {
            $result[0] = $text;
            $result[1] = false;
        } else {
            // regular expression to find typical sentence endings
            $pattern = '/([.?!]["\']?)\s/';
            // use regex to insert break indicator
            $text = preg_replace($pattern, '$1bRE@kH3re', $text);
            // use break indicator to create array of sentences
            $sentences = preg_split('/bRE@kH3re/', $text);
            // check relative length of array and requested number
            $howMany = count($sentences);
            $number = $howMany >= $number ? $number : $howMany;
            // rebuild extract and return as single string
            $remainder = array_splice($sentences, $number);
            $result[0] = implode(' ', $sentences);
            $result[1] = empty($remainder) ? false : true;
        }
        return $result;
    }
}
