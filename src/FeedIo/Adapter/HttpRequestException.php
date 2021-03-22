<?php

namespace FeedIo\Adapter;

use FeedIo\FeedIoException;

class HttpRequestException extends FeedIoException
{
    public function __construct(
        protected float $duration = 0
    ) {
        parent::__construct();
    }

    public function getDuration(): float
    {
        return $this->duration;
    }
}
