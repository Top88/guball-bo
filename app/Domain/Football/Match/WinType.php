<?php

namespace App\Domain\Football\Match;

enum WinType: string
{
    case FULL = 'full';
    case HALF = 'half';
}
