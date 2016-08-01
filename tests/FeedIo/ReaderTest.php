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
use Psr\Log\NullLogger;
use FeedIo\Rule\DateTimeBuilder;

class ReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Reader
     */
    protected $object;

    public function setUp()
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
        $client = $this->createMock('FeedIo\Adapter\ClientInterface');
        $client->expects($this->any())->method('getResponse')->will(
            $this->throwException(new ServerErrorException())
        );

        return $client;
    }

    /**
     * @return \FeedIo\Parser
     */
    protected function getParser()
    {
        $standard = $this->getMockForAbstractClass(
            '\FeedIo\StandardAbstract',
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

        $parser = new Parser($standard, new NullLogger());

        return $parser;
    }

    public function testLoadDocument()
    {
        $document = $this->object->loadDocument('<foo></foo>');
        $this->assertInstanceOf('\DomDocument', $document);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testLoadMalformedDocument()
    {
        $document = $this->object->loadDocument('<foo></bar>');
        $this->assertInstanceOf('\DomDocument', $document);
    }

    /**
     * @covers \FeedIo\Reader::addParser
     */
    public function testAddParser()
    {
        $parser = $this->getParser();
        $this->object->addParser($parser);
        $this->assertAttributeEquals(array($parser), 'parsers', $this->object);
    }

    public function testGetAccurateParser()
    {
        $this->object->addParser($this->getParser());
        $parser = $this->object->getAccurateParser(new \DOMDocument());

        $this->assertInstanceOf('\FeedIo\Parser', $parser);
    }

    /**
     * @expectedException \FeedIo\Reader\NoAccurateParserException
     */
    public function testGetAccurateParserFailure()
    {
        $this->object->getAccurateParser(new \DOMDocument());
    }

    public function testParseDocument()
    {
        $this->object->addParser($this->getParser());
        $feed = $this->object->parseDocument(new \DOMDocument(), new Feed());

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
        $this->assertInstanceOf('\DOMDocument', $result->getDocument());
    }

    /**
     * @covers \FeedIo\Reader::read
     */
    public function testReadWithoutModifiedSince()
    {
        $feed = new Feed();
        $this->object->addParser($this->getParser());
        $result = $this->object->read('fakeurl', $feed);
        $this->assertEquals(new \DateTime('@0'), $result->getModifiedSince());
    }

    /**
     * @covers \FeedIo\Reader::read
     * @expectedException \FeedIo\Reader\ReadErrorException
     */
    public function testReadException()
    {
        $reader = new Reader($this->getFaultyClientMock(), new NullLogger());
        $reader->read('fault', new Feed());
    }
}
