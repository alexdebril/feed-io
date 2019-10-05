<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31/10/14
 * Time: 12:14
 */
namespace FeedIo\Rule;

use FeedIo\Feed;

use \PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    /**
     * @var \FeedIo\Rule\Link
     */
    protected $object;

    const LINK = 'http://localhost';

    protected function setUp()
    {
        $this->object = new Link();
    }

    public function testGetNodeName()
    {
        $this->assertEquals('link', $this->object->getNodeName());
    }

    public function testSet()
    {
        $feed = new Feed();

        $this->object->setProperty($feed, new \DOMElement('link', self::LINK));
        $this->assertEquals(self::LINK, $feed->getLink());
    }

    public function testCreateElement()
    {
        $feed = new Feed();
        $feed->setLink(self::LINK);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $feed);

        $addedElement = $rootElement->firstChild;

        $this->assertEquals(self::LINK, $addedElement ->nodeValue);
        $this->assertEquals('link', $addedElement ->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><link>' . self::LINK .'</link></feed>', $document->saveXML());
    }
}
