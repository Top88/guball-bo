<?php

namespace App\Traits;

trait HelpersTrait
{
    public static function enrichTime(string $time): string
    {
        $times = explode(':', $time);

        return self::fillTime((int) $times[0] ?? 0).':'.self::fillTime((int) $times[1] ?? 0).':'.self::fillTime((int) $time[2] ?? 0);
    }

    public static function fillTime(int $digit): string
    {
        return $digit < 10 ? "0{$digit}" : $digit;
    }
}
