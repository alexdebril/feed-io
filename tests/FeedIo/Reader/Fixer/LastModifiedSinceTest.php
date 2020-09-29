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

class LastModifiedSinceTest extends TestCase
{

    /**
     * @var LastModifiedSince
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new LastModifiedSince();
        $this->object->setLogger(new NullLogger());
    }

    public function testCorrect()
    {
        $modifiedSince = new \DateTime('2018-01-01');
        $document = new Document('');
        $response = new NullResponse();

        $result =  new Result($document, new Feed(), $modifiedSince, $response, 'http://localhost/test.rss');
        $feed = $result->getFeed();

        $this->assertNull($feed->getLastModified());
        $this->object->correct($result);

        $this->assertEquals($modifiedSince, $feed->getLastModified());
    }

    public function testDontCorrect()
    {
        $modifiedSince = new \DateTime('2018-01-01');
        $document = new Document('');
        $response = new NullResponse();

        $result =  new Result($document, $this->getFeed(), $modifiedSince, $response, 'http://localhost/test.rss');
        $feed = $result->getFeed();

        $this->assertNull($feed->getLastModified());
        $this->object->correct($result);

        $this->assertNull($feed->getLastModified());
    }

    protected function getFeed()
    {
        $item = new Item();
        $item->setLastModified(new \DateTime('2013-01-01'));

        $feed = new Feed();
        $feed->add($item);

        return $feed;
    }
}
