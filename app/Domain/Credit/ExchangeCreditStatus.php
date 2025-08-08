<?php

namespace App\Domain\Credit;

enum ExchangeCreditStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}