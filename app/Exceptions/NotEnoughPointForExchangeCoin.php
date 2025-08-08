<?php

namespace App\Exceptions;

use Exception;

class NotEnoughPointForExchangeCoin extends Exception
{
    public function __construct(int|float $cost)
    {
        parent::__construct(__('website.exception.not_enough_point_for_exchange_to_coin', ['point' => $cost]));
    }
}
