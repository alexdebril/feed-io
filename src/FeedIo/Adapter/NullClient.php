<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Adapter;

/**
 * Fake HTTP client
 */
class NullClient implements ClientInterface
{

    /**
     * @param  string                            $url
     * @param  \DateTime                         $modifiedSince
     * @return \FeedIo\Adapter\ResponseInterface
     */
    public function getResponse(string $url, \DateTime $modifiedSince) : ResponseInterface
    {
        return new NullResponse();
    }
}
