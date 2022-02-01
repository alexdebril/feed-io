<?php

declare(strict_types=1);

namespace FeedIo\Adapter;

use Psr\Http\Message\ResponseInterface;

class ServerErrorException extends HttpRequestException
{
    public function __construct(
        protected ResponseInterface $response,
        float $duration = 0
    ) {
        parent::__construct(
            'internal server error',
            $duration
        );
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
