<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CostNotEnoughException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Cost not enough'.$message, $code, $previous);
    }
}
