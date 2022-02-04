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

use FeedIo\Feed\Item;
use FeedIo\Parser\XmlParser as Parser;
use FeedIo\Reader\Document;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Rss;
use Psr\Log\NullLogger;

use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @var \FeedIo\Parser
     */
    protected $object;

    public function setUp(): void
    {
        $date = new DateTimeBuilder();
        $date->addDateFormat(\DateTime::ATOM);
        $standard = $this->getMockForAbstractClass(
            '\FeedIo\StandardAbstract',
            array($date),
            'StandardMock',
            true,
            true,
            true,
            ['canHandle', 'getMainElement', 'buildFeedRuleSet']
        );
        $standard->expects($this->any())->method('canHandle')->will($this->returnValue(true));
        $standard->expects($this->any())->method('buildFeedRuleSet')->will($this->returnValue(new RuleSet()));
        $standard->expects($this->any())->method('getMainElement')->will($this->returnValue(new \DOMElement('test')));

        $this->object = new Parser($standard, new NullLogger());
    }

    public function testParse()
    {
        $document = new \DOMDocument();
        $document->loadXML('<feed><items></items></feed>');
        $feed = $this->object->parse(new Document($document->saveXml()), new Feed());
        $this->assertInstanceOf('FeedIo\Feed', $feed);
    }

    public function testParseNode()
    {
        $document = new \DOMDocument();
        $xml = <<<XML
        <channel>
            <title>feed-io</title>
            <link>https://github.com/alexdebril/feed-io</link>
            <description>feed-io is a library</description>
        </channel>
XML;
        $document->loadXML($xml);
        $feed = new Feed();
        $this->object->parseNode($feed, $document->documentElement, new RuleSet());

        $this->assertInstanceOf('\Iterator', $feed->getElementIterator('description'));
        $iterator = $feed->getElementIterator('description');
        $count = 0;
        foreach ($iterator as $element) {
            $this->assertInstanceOf('\FeedIo\Feed\Node\ElementInterface', $element);
            $this->assertEquals('feed-io is a library', $element->getValue());
            $count++;
        }
        $this->assertEquals(1, $count);
    }

    public function testParseBadDocument()
    {
        $document = new \DOMDocument();
        $document->loadXML('<feed><items></items></feed>');

        $standard = $this->getMockForAbstractClass(
            '\FeedIo\StandardAbstract',
            array(new DateTimeBuilder())
        );
        $standard->expects($this->any())->method('canHandle')->will($this->returnValue(false));
        $parser = new Parser($standard, new NullLogger());

        $this->expectException('\FeedIo\Parser\UnsupportedFormatException');
        $parser->parse(new Document($document->saveXML()), new Feed());
    }

    public function testCheckStructure()
    {
        $rss = <<<RSS
<rss version="2.0">
    <channel>
        <title>RSS Title</title>
    </channel>
</rss>
RSS;
        $document = new \DOMDocument();
        $document->loadXML($rss);
        $this->assertTrue(
            $this->object->checkBodyStructure(new Document($document->saveXML()), array('channel', 'title'))
        );
    }

    public function testCheckBadStructure()
    {
        $document = new \DOMDocument();
        $document->loadXML('<rss></rss>');
        $this->expectException('\FeedIo\Parser\MissingFieldsException');
        $this->assertInstanceOf(
            '\FeedIo\Parser',
            $this->object->checkBodyStructure(new Document($document->saveXML()), array('channel'))
        );
    }

    public function testParseEmptyRssFeed()
    {
        $rss = <<<RSS
<rss version="2.0"></rss>
RSS;
        $document = new \DOMDocument();
        $document->loadXML($rss);
        $parser = new Parser(new Rss(
            new DateTimeBuilder()
        ), new NullLogger());
        $this->expectException('\FeedIo\Parser\MissingFieldsException');
        $parser->parse(new Document($document->saveXML()), new Feed());
    }
}
