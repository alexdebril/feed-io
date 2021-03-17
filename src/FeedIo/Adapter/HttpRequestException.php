<?php


namespace FeedIo\Adapter;

use FeedIo\FeedIoException;

class HttpRequestException extends FeedIoException
{
    protected int $duration = 0;

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }
}
