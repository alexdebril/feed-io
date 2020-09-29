<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31/10/14
 * Time: 12:14
 */
namespace FeedIo\Rule;

use FeedIo\Feed\Item;

use \PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{

    /**
     * @var \FeedIo\Rule\Link
     */
    protected $object;

    const LINK = 'http://localhost';

    protected function setUp(): void
    {
        $this->object = new Link();
    }

    public function testGetNodeName()
    {
        $this->assertEquals('link', $this->object->getNodeName());
    }

    public function testSet()
    {
        $item = new Item();

        $this->object->setProperty($item, new \DOMElement('link', self::LINK));
        $this->assertEquals(self::LINK, $item->getLink());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setLink(self::LINK);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;

        $this->assertEquals(self::LINK, $addedElement ->nodeValue);
        $this->assertEquals('link', $addedElement ->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><link>' . self::LINK .'</link></feed>', $document->saveXML());
    }
}
