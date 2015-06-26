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

class ResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Reader\Result
     */
    protected $object;

    protected $modifiedSince;

    protected $resultDate;

    protected function setUp()
    {
        $this->modifiedSince = new \DateTime('-10 days');
        $this->resultDate = new \DateTime();

        $response = $this->getMockForAbstractClass('\FeedIo\Adapter\ResponseInterface');
        $this->object = new Result(
            new \DOMDocument(),
            new Feed(),
            $this->modifiedSince,
            $response,
            'http://localhost'
        );
    }

    public function testResult()
    {
        $this->assertInstanceOf('\DomDocument', $this->object->getDocument());
        $this->assertInstanceOf('\FeedIo\FeedInterface', $this->object->getFeed());
        $this->assertEquals($this->resultDate, $this->object->getDate());
        $this->assertEquals($this->modifiedSince, $this->object->getModifiedSince());
        $this->assertInstanceOf('\FeedIo\Adapter\ResponseInterface', $this->object->getresponse());
        $this->assertEquals('http://localhost', $this->object->getUrl());
    }
}
