<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

use FeedIo\Adapter\ServerErrorException;
use FeedIo\Parser\XmlParser;
use Psr\Log\NullLogger;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Reader\Document;

use \PHPUnit\Framework\TestCase;

class ExplorerTest extends TestCase
{

    /**
     * @var \FeedIo\Explorer
     */
    protected $object;

    public function setUp(): void
    {
        $this->object = new Explorer(
            $this->getClientMock(),
            new NullLogger()
        );
    }

    /**
     * @return \FeedIo\Adapter\ClientInterface
     */
    protected function getClientMock()
    {
        $html = file_get_contents(dirname(__FILE__)."/../samples/discovery.html");
        $client = $this->createMock('FeedIo\Adapter\ClientInterface');
        $response = $this->createMock('FeedIo\Adapter\ResponseInterface');
        $response->expects($this->any())->method('getBody')->will($this->returnValue($html));
        $client->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        return $client;
    }

    /**
     * @covers \FeedIo\Reader::addParser
     */
    public function testDiscover()
    {
        $feeds = $this->object->discover('https://exmple.org/feed.atom');

        $this->assertEquals(['http://example.org/feed.xml', 'http://example.org/comments.xml'], $feeds);
    }
}
