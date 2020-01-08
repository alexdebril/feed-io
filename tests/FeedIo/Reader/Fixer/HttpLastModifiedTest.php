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
use FeedIo\Reader\Document;
use FeedIo\Reader\Result;
use Psr\Log\NullLogger;

use \PHPUnit\Framework\TestCase;

class HttpLastModifiedTest extends TestCase
{

    /**
     * @var HttpLastModified
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new HttpLastModified();
        $this->object->setLogger(new NullLogger());
    }

    public function testCorrect()
    {
        $result = $this->getResultMock();
        $feed = $result->getFeed();

        $this->assertNull($feed->getLastModified());
        $this->object->correct($result);

        $this->assertEquals(new \DateTime('@0'), $feed->getLastModified());
    }

    public function testSkipCorrectIfLastModifiedNotNull()
    {
        $result = $this->getResultMock();
        $feed = $result->getFeed();
        $feed->setLastModified(new \DateTime('@3'));

        $this->assertNotNull($feed->getLastModified());
        $this->object->correct($result);

        $this->assertEquals(new \DateTime('@3'), $feed->getLastModified());
    }

    protected function getResultMock(): Result
    {
        /** @var Document $document */
        $document = $this->createMock(Document::class);
        /** @var Feed $feed */
        $feed = new Feed();
        /** @var ResponseInterface $response */
        $response = new NullResponse();

        return new Result($document, $feed, new \DateTime('@0'), $response, 'http://localhost/test.rss');
    }
}
