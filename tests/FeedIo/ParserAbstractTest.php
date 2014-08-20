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
use Psr\Log\NullLogger;

class ParserAbstractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\ParserAbstract
     */
    protected $object;

    public function setUp()
    {
        $this->object = $this->getMockForAbstractClass('\FeedIo\ParserAbstract', array(new NullLogger()));
        $this->object->expects($this->any())->method('canHandle')->will($this->returnValue(true));
        $this->object->expects($this->any())->method('parseBody')->will($this->returnValue(new Feed()));
    }

    public function testSetDateFormats()
    {
        $formats = array(\DateTime::ATOM);
        $this->object->setDateFormats($formats);
        $this->assertAttributeEquals($formats, 'dateFormats', $this->object);
    }

    public function testParse()
    {
        $document = new \DOMDocument();
        $document->loadXML('<feed><items></items></feed>');
        $feed = $this->object->parse($document, new Feed(), new \DateTime());
        $this->assertInstanceOf('FeedIo\Feed', $feed);
    }

    public function testIsValid()
    {
        $item = new Item();
        $item->setLastModified(new \DateTime('-1day'));

        $this->assertTrue($this->object->isValid($item, new \DateTime('-2day')));
        $this->assertFalse($this->object->isValid($item, new \DateTime()));
        $this->assertFalse($this->object->isValid($item, new \DateTime('-1day')));
    }

    public function testGuessDateFormat()
    {
        $formats = array(\DateTime::ATOM, \DateTime::RFC1036);
        $this->object->setDateFormats($formats);
        $date = new \DateTime();
        $format = $this->object->guessDateFormat($date->format(\DateTime::ATOM));
        $this->assertEquals(\DateTime::ATOM, $format);
    }

}
 