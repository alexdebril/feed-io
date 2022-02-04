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

use FeedIo\Reader\ResultMockFactory;
use Psr\Log\NullLogger;

use PHPUnit\Framework\TestCase;

class HttpLastModifiedTest extends TestCase
{
    /**
     * @var HttpLastModified
     */
    protected $object;

    /**
     * @var ResultMockFactory
     */
    protected $resultMockFactory;

    protected function setUp(): void
    {
        $this->object = new HttpLastModified();
        $this->object->setLogger(new NullLogger());
        $this->resultMockFactory = new ResultMockFactory();
    }

    public function testCorrect()
    {
        $result = $this->resultMockFactory->make();
        $feed = $result->getFeed();

        $this->assertNull($feed->getLastModified());
        $this->object->correct($result);

        $this->assertEquals(new \DateTime('@0'), $feed->getLastModified());
    }

    public function testSkipCorrectIfLastModifiedNotNull()
    {
        $result = $this->resultMockFactory->make();
        $feed = $result->getFeed();
        $feed->setLastModified(new \DateTime('@3'));

        $this->assertNotNull($feed->getLastModified());
        $this->object->correct($result);

        $this->assertEquals(new \DateTime('@3'), $feed->getLastModified());
    }
}
