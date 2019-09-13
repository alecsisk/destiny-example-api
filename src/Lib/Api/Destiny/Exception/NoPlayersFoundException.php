<?php

namespace App\Lib\Api\Destiny\Exception;

use Throwable;

class NoPlayersFoundException extends ApiException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}