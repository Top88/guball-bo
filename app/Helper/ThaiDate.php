<?php

namespace App\Helper;

use Carbon\Carbon;

class ThaiDate
{
    public const WEEK_DAY = ['อาทิตย์','จันทร์ฺ', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    public const YEAR_THAI = 543;

    public const MONTH = [
        'มกราคม',
        'กุมภาพันธ์',
        'มีนาคม',
        'เมษายน',
        'พฤษภาคม',
        'มิถุนายน',
        'กรกฎาคม',
        'สิงหาคม',
        'กันยายน',
        'ตุลาคม',
        'พฤศจิกายน',
        'ธันวาคม',
    ];

    /**
     *
     */
    public const SHORT_MONTH = [
        'ม.ค.',
        'ก.พ.',
        'มี.ค.',
        'เม.ย.',
        'พ.ค.',
        'มิ.ย.',
        'ก.ค.',
        'ส.ค.',
        'ก.ย.',
        'ต.ค.',
        'พ.ย.',
        'ธ.ค.',
    ];

    /**
     * @param string|null $date
     * @param string $defaultMessage
     * @param bool $isShort
     * @return string
     */
    public static function toDateTime(?string $date, string $defaultMessage = 'No date', bool $isShort = false): string
    {
        $date = self::getDate($date);
        if ($date === null) {
            return $defaultMessage;
        }
        $month = $isShort ? self::SHORT_MONTH[$date->month - 1] : self::MONTH[$date->month - 1];

        return $date->day . ' ' . $month . ' ' . ($date->year + self::YEAR_THAI);
    }

    /**
     * @param string|null $date
     * @param string $defaultMessage
     * @return string
     */
    public static function toTime(?string $date, string $defaultMessage = 'No Time'): string
    {
        $date = self::getDate($date);
        if ($date === null) {
            return $defaultMessage;
        }

        return $date->format('H:i') . ' น.';
    }

    /**
     * @param string|null $date
     * @param string $defaultMessage
     * @return string
     */
    public static function toShortMonthYear(?string $date, string $defaultMessage = 'No Time'): string
    {
        $date = self::getDate($date);
        if ($date === null) {
            return $defaultMessage;
        }

        return self::SHORT_MONTH[$date->month - 1] . ' ' . substr(($date->year + self::YEAR_THAI), -2);
    }

    /**
     * @param string|null $date
     * @return Carbon|null
     */
    private static function getDate(?string $date): ?Carbon
    {
        if ($date === null) {
            return null;
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $date);
    }
}
