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

class ReaderTest extends TestCase
{
    /**
     * @var \FeedIo\Reader
     */
    protected $object;

    public function setUp(): void
    {
        $this->object = new Reader(
            $this->getClientMock(),
            new NullLogger()
        );
    }

    /**
     * @return \FeedIo\Adapter\ClientInterface
     */
    protected function getClientMock()
    {
        $client = $this->createMock('FeedIo\Adapter\ClientInterface');
        $response = $this->createMock('FeedIo\Adapter\ResponseInterface');
        $response->expects($this->any())->method('getBody')->will($this->returnValue('<rss></rss>'));
        $client->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        return $client;
    }

    /**
     * @return \FeedIo\Adapter\ClientInterface
     */
    protected function getFaultyClientMock()
    {
        $response = $this->createMock('Psr\Http\Message\ResponseInterface');
        $client = $this->createMock('FeedIo\Adapter\ClientInterface');
        $client->expects($this->any())->method('getResponse')->will(
            $this->throwException(new ServerErrorException($response, 0))
        );

        return $client;
    }

    /**
     * @return \FeedIo\Parser
     */
    protected function getParser()
    {
        $standard = $this->getMockForAbstractClass(
            '\FeedIo\Standard\XmlAbstract',
            array(new DateTimeBuilder())
        );
        $standard->expects($this->any())->method('canHandle')->will($this->returnValue(true));
        $standard->expects($this->any())->method('buildFeedRuleSet')->will($this->returnValue(new RuleSet()));
        $standard->expects($this->any())->method('buildItemRuleSet')->will($this->returnValue(new RuleSet()));
        $file = dirname(__FILE__)."/../samples/rss/sample-rss.xml";
        $domDocument = new \DOMDocument();
        $domDocument->load($file, LIBXML_NOBLANKS | LIBXML_COMPACT);
        $standard->expects($this->any())->method('getMainElement')->will($this->returnValue(
            $domDocument->documentElement->getElementsByTagName('channel')->item(0)
        ));

        $parser = new XmlParser($standard, new NullLogger());

        return $parser;
    }

    public function testGetAccurateParser()
    {
        $this->object->addParser($this->getParser());
        $parser = $this->object->getAccurateParser(new Document(''));

        $this->assertInstanceOf('\FeedIo\ParserAbstract', $parser);
    }

    public function testGetAccurateParserFailure()
    {
        $this->expectException('\FeedIo\Reader\NoAccurateParserException');
        $this->object->getAccurateParser(new Document(''));
    }

    public function testParseDocument()
    {
        $this->object->addParser($this->getParser());
        $feed = $this->object->parseDocument(new Document('<feed></feed>'), new Feed());

        $this->assertInstanceOf('\FeedIo\Feed', $feed);
        $this->assertEquals('This is an example of an RSS feed', $feed->getValue('description'));
    }

    /**
     * @covers \FeedIo\Reader::read
     */
    public function testReadWithModifiedSince()
    {
        $feed = new Feed();
        $this->object->addParser($this->getParser());
        $modifiedSince = new \DateTime();
        $url = 'http://localhost';
        $result = $this->object->read($url, $feed, $modifiedSince);

        $this->assertEquals($url, $result->getUrl());
        $this->assertEquals($modifiedSince, $result->getModifiedSince());
        $this->assertInstanceOf('\FeedIo\Reader\Document', $result->getDocument());
    }

    /**
     * @covers \FeedIo\Reader::read
     */
    public function testReadWithoutModifiedSince()
    {
        $feed = new Feed();
        $this->object->addParser($this->getParser());
        $result = $this->object->read('fakeurl', $feed);
        $this->assertEquals(new \DateTime('1800-01-01'), $result->getModifiedSince());
    }

    public function testReadException()
    {
        $reader = new Reader($this->getFaultyClientMock(), new NullLogger());
        $this->expectException('\FeedIo\Reader\ReadErrorException');
        $reader->read('fault', new Feed());
    }

    public function testHandleResponse()
    {
        $feed = new Feed();

        $standard = $this->getMockForAbstractClass(
            '\FeedIo\Standard\XmlAbstract',
            [new DateTimeBuilder()]
        );
        $standard->expects($this->any())->method('canHandle')->will($this->returnValue(true));

        $parser = $this->getMockForAbstractClass(
            '\FeedIo\ParserAbstract',
            [$standard, new NullLogger()]
        );

        $parser->expects($this->once())->method('checkBodyStructure')->will($this->returnValue(true));
        $parser->expects($this->once())->method('parseContent')->will($this->returnValue($feed));

        $this->object->addParser($parser);

        $response = $this->getMockForAbstractClass('\FeedIo\Adapter\ResponseInterface');
        $response->expects($this->once())->method('isModified')->will($this->returnValue(true));
        $response->expects($this->any())->method('getBody')->will($this->returnValue(''));

        $this->object->handleResponse($response, $feed);
    }

    public function testHandleEmptyResponse()
    {
        $feed = new Feed();

        $response = $this->getMockForAbstractClass('\FeedIo\Adapter\ResponseInterface');
        $response->expects($this->once())->method('isModified')->will($this->returnValue(false));
        $response->expects($this->any())->method('getBody')->will($this->returnValue(''));

        $this->object->handleResponse($response, $feed);
    }
}
