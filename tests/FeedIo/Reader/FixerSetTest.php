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

use \PHPUnit\Framework\TestCase;

class FixerSetTest extends TestCase
{
    public function testCorrect()
    {
        $fixer = new FixerMock();
        $fixerSet = new FixerSet();
        $fixerSet->add($fixer);

        $result = $this->getResultMock();
        $feed = $result->getFeed();

        $fixerSet->correct($result);

        $this->assertEquals('corrected', $feed->getTitle());
    }

    protected function getResultMock(): Result
    {
        /** @var Document $document */
        $document = $this->createMock(Document::class);
        /** @var Feed $feed */
        $feed =  new Feed();
        /** @var ResponseInterface $response */
        $response = new NullResponse();

        return new Result($document, $feed, new \DateTime('@0'), $response, 'http://localhost/test.rss');
    }
}
