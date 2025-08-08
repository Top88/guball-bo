<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct($message = '', $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message ?: 'User Not found', $code, $previous);
    }
}
