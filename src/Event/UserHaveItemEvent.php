<?php


namespace App\Event;


class UserHaveItemEvent
{
    /**
     * @var string
     */
    private $nickname;
    /**
     * @var bool
     */
    private $userHaveItem;

    public function __construct(string $nickname, bool $userHaveItem)
    {
        $this->nickname = $nickname;
        $this->userHaveItem = $userHaveItem;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return bool
     */
    public function isUserHaveItem(): bool
    {
        return $this->userHaveItem;
    }
}