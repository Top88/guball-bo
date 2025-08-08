<?php

namespace App\Infrastructure\User\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';
    case PENDING = 'pending';
}
