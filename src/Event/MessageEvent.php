<?php


namespace App\Event;


use Symfony\Contracts\EventDispatcher\Event;

class MessageEvent extends Event
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}