<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader\Fixer;

use FeedIo\Adapter\NullResponse;
use FeedIo\Adapter\ResponseInterface;
use FeedIo\Feed;
use FeedIo\Feed\Item;
use FeedIo\Reader\Document;
use FeedIo\Reader\Result;
use Psr\Log\NullLogger;

use \PHPUnit\Framework\TestCase;

class PublicIdTest extends TestCase
{

    /**
     * @var PublicId
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new PublicId();
        $this->object->setLogger(new NullLogger());
    }

    public function testCorrect()
    {
        $result = $this->getResultMock();
        $feed = $result->getFeed();

        $this->assertNull($feed->getPublicId());
        $this->object->correct($result);

        $this->assertEquals($feed->getLink(), $feed->getPublicId());

        foreach ($feed as $idx => $item) {
            if (0 === $idx) {
                $this->assertEquals($item->getLink(), $item->getPublicId());
            } else {
                $this->assertEquals('2', $item->getPublicId());
            }
        }
    }

    protected function getFeed()
    {
        $item1 = new Item();
        $item1->setLink('http://localhost/1');

        $item2 = new Item();
        $item2->setLink('http://localhost/2');
        $item2->setPublicId('2');

        $feed = new Feed();
        $feed->add($item1)->setLink('http://localhost/');
        $feed->add($item2);

        return $feed;
    }

    protected function getResultMock(): Result
    {
        /** @var Document $document */
        $document = $this->createMock(Document::class);
        /** @var Feed $feed */
        $feed = $this->getFeed();
        /** @var ResponseInterface $response */
        $response = new NullResponse();

        return new Result($document, $feed, new \DateTime('@0'), $response, 'http://localhost/test.rss');
    }
}
