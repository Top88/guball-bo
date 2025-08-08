<?php

namespace App\Infrastructure\Matches\Enums;

enum MatchStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case FINISHED = 'finished';
}
