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

use PHPUnit\Framework\TestCase;

class ExplorerTest extends TestCase
{
    /**
     * @var \FeedIo\Explorer
     */
    protected $object;

    public function setUp(): void
    {
        
    }

    protected function createTestObject(string $htmlPath)
    {
        $this->object = new Explorer(
            $this->getClientMock($htmlPath),
            new NullLogger()
        );
    }

    /**
     * @return \FeedIo\Adapter\ClientInterface
     */
    protected function getClientMock(string $htmlPath)
    {
        $html = file_get_contents(dirname(__FILE__) . $htmlPath);
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
        $this->createTestObject("/../samples/discovery.html");
        $feeds = $this->object->discover('https://exmple.org/feed.atom');

        $this->assertEquals(['http://example.org/feed.xml', 'http://example.org/comments.xml'], $feeds);
    }

    /**
     * @covers \FeedIo\Reader::addParser
     */
    public function testDiscoverHttps()
    {
        $this->createTestObject("/../samples/discovery-https.html");
        $feeds = $this->object->discover('https://exmple.org/feed.atom');

        $this->assertEquals(['https://example.org/feed.xml', 'https://example.org/comments.xml'], $feeds);
    }

    /**
     * @covers \FeedIo\Reader::addParser
     */
    public function testDiscoverProtocolless()
    {
        $this->createTestObject("/../samples/discovery-dualprotocol.html");
        $feeds = $this->object->discover('https://exmple.org/feed.atom');

        $this->assertEquals(['https://example.org/feed.xml', 'https://example.org/comments.xml'], $feeds);
    }
}
