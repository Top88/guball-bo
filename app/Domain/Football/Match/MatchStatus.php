<?php

namespace App\Domain\Football\Match;

enum MatchStatus
{
    case active;
    case inactive;
    case finished;
}
