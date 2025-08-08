<?php

namespace App\Domain\Football;

enum RateType: string
{
    case NORMAL = 'normal';
    case PP = 'pp';

    public static function allCases(): array
    {
        return array_column(self::cases(), 'value');
    }
}
