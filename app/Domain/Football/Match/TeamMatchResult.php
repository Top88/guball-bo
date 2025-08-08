<?php

namespace App\Domain\Football\Match;

enum TeamMatchResult: string
{
    case HOME = 'home';
    case AWAY = 'away';
    case DRAW = 'draw';
}
