<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader;

use FeedIo\Adapter\NullResponse;
use FeedIo\Adapter\ResponseInterface;
use FeedIo\Feed;

class ResultMockFactory
{
    public function make(): Result
    {
        return $this->makeWithFeed(new Feed());
    }

    public function makeWithFeed(Feed $feed): Result
    {
        /** @var Document $document */
        $document = new Document('');
        /** @var ResponseInterface $response */
        $response = new NullResponse();

        return new Result($document, $feed, new \DateTime('@0'), $response, 'http://localhost/test.rss');
    }
}
