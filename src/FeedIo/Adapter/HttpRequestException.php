<?php


namespace FeedIo\Adapter;

use FeedIo\FeedIoException;

class HttpRequestException extends FeedIoException
{
    /**
     * @var int
     */
    protected $duration;

    /**
     * @return mixed
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }
}
