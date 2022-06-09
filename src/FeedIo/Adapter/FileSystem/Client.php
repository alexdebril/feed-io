<?php

declare(strict_types=1);

namespace FeedIo\Adapter\FileSystem;

use DateTime;
use FeedIo\Adapter\ClientInterface;
use FeedIo\Adapter\NotFoundException;
use FeedIo\Adapter\ResponseInterface;

/**
 * Filesystem client
 */
class Client implements ClientInterface
{
    /**
     * @param  string                            $path
     * @param  \DateTime                         $modifiedSince
     * @return \FeedIo\Adapter\ResponseInterface
     *@throws \FeedIo\Adapter\NotFoundException
     */
    public function getResponse(string $path, DateTime $modifiedSince = null): ResponseInterface
    {
        if (file_exists($path)) {
            return new Response(
                file_get_contents($path),
                new DateTime('@'.filemtime($path))
            );
        }

        throw new NotFoundException();
    }
}
