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
use FeedIo\Parser\Date;
use Psr\Log\NullLogger;

class ParserAbstractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\ParserAbstract
     */
    protected $object;

    public function setUp()
    {
        $date = new Date();
        $date->addDateFormat(\DateTime::ATOM);
        $this->object = $this->getMockForAbstractClass(
            '\FeedIo\ParserAbstract',
            array($date, new NullLogger())
        );
        $this->object->expects($this->any())->method('canHandle')->will($this->returnValue(true));
        $this->object->expects($this->any())->method('parseBody')->will($this->returnValue(new Feed()));
        $this->object->expects($this->any())->method('getMainElement')->will($this->returnValue(new \DOMElement('test')));
    }

    public function testParse()
    {
        $document = new \DOMDocument();
        $document->loadXML('<feed><items></items></feed>');
        $feed = $this->object->parse($document, new Feed());
        $this->assertInstanceOf('FeedIo\Feed', $feed);
    }

    public function testParseRootNode()
    {
        $document = new \DOMDocument();
        $document->loadXML('<channel><description>feed-io is a library</description></channel>');
        $feed = new Feed();
        $this->object->parseRootNode($document->documentElement, $feed);

        $this->assertEquals('feed-io is a library', $feed->getDescription());
    }

    public function testParseItemNode()
    {
        $document = new \DOMDocument();
        $item = <<<XML
        <item>
            <title>My Great Title</title>
            <author>a.debril</author>
        </item>
XML;

        $document->loadXML($item);
        $feed = new Feed();
        $item = $this->object->parseItemNode($document->documentElement, $feed);

        $this->assertEquals('My Great Title', $item->getTitle());
        $this->assertEquals('a.debril', $item->getOptionalFields()->get('author'));
    }

    public function testIsValid()
    {
        $item = new Item();
        $item->setLastModified(new \DateTime('-1day'));

        $filter = $this->getMockForAbstractClass('\FeedIo\FilterInterface');
        $filter->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->object->addFilter($filter);
        $this->assertTrue($this->object->isValid($item));
    }

    public function testSetLastModifiedSince()
    {
        $date = new \DateTime();
        $feed = $this->object->setLastModifiedSince(new Feed(), $date->format(\DateTime::ATOM));
        $this->assertEquals($date, $feed->getLastModified());
    }

}
 