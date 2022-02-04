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

use FeedIo\Feed;
use FeedIo\Reader\Document;

use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    /**
     * @var \FeedIo\Reader\Result
     */
    protected $object;

    protected $modifiedSince;

    protected $resultDate;

    protected function setUp(): void
    {
        $this->modifiedSince = new \DateTime('-10 days');
        $this->resultDate = new \DateTime();

        $response = $this->getMockForAbstractClass('\FeedIo\Adapter\ResponseInterface');
        $this->object = new Result(
            new Document('<feed></feed>'),
            new Feed(),
            $this->modifiedSince,
            $response,
            'http://localhost'
        );
    }

    public function testResult()
    {
        $this->assertInstanceOf('\FeedIo\Reader\Document', $this->object->getDocument());
        $this->assertInstanceOf('\FeedIo\FeedInterface', $this->object->getFeed());
        $this->assertEquals($this->resultDate->format(\DateTime::ATOM), $this->object->getDate()->format(\DateTime::ATOM));
        $this->assertEquals($this->modifiedSince->format(\DateTime::ATOM), $this->object->getModifiedSince()->format(\DateTime::ATOM));
        $this->assertInstanceOf('\FeedIo\Adapter\ResponseInterface', $this->object->getresponse());
        $this->assertEquals('http://localhost', $this->object->getUrl());
    }
}
