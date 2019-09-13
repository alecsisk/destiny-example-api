<?php


namespace App\Lib\Api\Destiny\Exception;


use Throwable;

class NoPlayersFoundException extends \Exception
{
    /**
     * @var string
     */
    private $nickname;

    public function __construct(string $nickname, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }
}