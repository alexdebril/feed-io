<?php

declare(strict_types=1);

namespace FeedIo\Adapter;

use DateTime;

/**
 * Fake HTTP client
 */
class NullClient implements ClientInterface
{
    /**
     * @param  string                            $url
     * @param  DateTime|null                         $modifiedSince
     * @return \FeedIo\Adapter\ResponseInterface
     */
    public function getResponse(string $url, DateTime $modifiedSince = null): ResponseInterface
    {
        return new NullResponse();
    }
}
