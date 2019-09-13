<?php


namespace App\Event;


class ProgressEvent
{
    /**
     * @var int
     */
    private $current;
    /**
     * @var int
     */
    private $max;

    public function __construct(int $current, int $max)
    {
        $this->current = $current;
        $this->max = $max;
    }

    /**
     * @return int
     */
    public function getCurrent(): int
    {
        return $this->current;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }
}