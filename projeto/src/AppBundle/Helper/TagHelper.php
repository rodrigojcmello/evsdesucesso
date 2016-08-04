<?php

namespace AppBundle\Helper;

abstract class TagHelper
{
    /**
     * @param string $string
     *
     * @return array
     */
    public static function generateTags($string)
    {
        $string = mb_strtolower($string);
        $string = preg_replace('/[®&().]/', '', $string);
        $string = preg_replace('/[\s+-]/', ' ', $string);
        $string = trim($string);

        return array_unique(array_filter(array_map(function($part) {
            return trim($part);
        }, mb_split('\b(\s+|a|as|com|da|de|dos|e|em|o|os|para)\b', $string))));
    }
}