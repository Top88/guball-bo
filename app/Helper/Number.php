<?php
namespace App\Helper;

class Number
{
    public static function shortNumberUnit(float $number): string
    {
        $thousand = $number / 1000;
        if ($thousand >= 1 && $thousand < 1000) {
            return "K+";
        }
        $millian = $number / 1000000;
        if ($millian >= 1 && $millian < 1000) {
            return "KM+";
        }
        return '+';
    }
}