<?php


namespace App\Lib\Api\Destiny\Handler;

use \Exception;
use Throwable;

class HandlerException extends Exception
{
    private $type;

    public function __construct($message = "", $type = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


    public function getDetails(): string
    {
        return $this->type . ' : ' . $this->message;
    }


    public function getUniqueName(): string
    {
        return $this->type . $this->getCode();
    }
}