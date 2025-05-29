<?php

namespace App\Helpers;

class Helper
{

    public static function truncateText($text, $maxWords = 20)
    {
        $words = explode(' ', $text);
        if (count($words) > $maxWords) {
            $text = implode(' ', array_slice($words, 0, $maxWords)) . '...';
        }
        return $text;
    }
}
