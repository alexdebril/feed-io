<?php

namespace FeedIo\Adapter;

use FeedIo\FeedIoException;

class HttpRequestException extends FeedIoException
{
    public function __construct(
        string $message = '',
        protected float $duration = 0
    ) {
        parent::__construct($message);
    }

    public function getDuration(): float
    {
        return $this->duration;
    }
}
